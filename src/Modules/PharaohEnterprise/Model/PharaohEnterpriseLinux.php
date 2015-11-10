<?php

Namespace Model;

class PharaohEnterpriseLinux extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    protected $username ;
    protected $apiKey ;

    public function __construct($params) {
        parent::__construct($params);
//        $this->installCommands = array(
//            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("MacPorts", "httpd")) ),
//            array("method"=> array("object" => $this, "method" => "apacheRestart", "params" => array())) );
//        $this->uninstallCommands = array(
//            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("MacPorts", "httpd")) ),
//            array("method"=> array("object" => $this, "method" => "apacheRestart", "params" => array())) );
        $this->programNameMachine = "PharaohEnterprise"; // command and app dir name
        $this->programNameFriendly = "PT Enterprise"; // 12 chars
        $this->programNameInstaller = "Pharaoh Enterprise - upgrade from open source to Enterprise";
        $this->statusCommand = "httpd -v" ;
        $this->versionInstalledCommand = SUDOPREFIX."httpd -v" ;
        $this->versionRecommendedCommand = SUDOPREFIX."httpd -v" ;
        $this->versionLatestCommand = SUDOPREFIX."httpd -v" ;
        $this->initialize();
    }

    public function setInstallCommands() {
        $ray =
            array(
                array("method"=> array("object" => $this, "method" => "initialiseEnterprise", "params" => array()) ),
                array("method"=> array("object" => $this, "method" => "installEnterprise", "params" => array()) ),
            ) ;
        $this->installCommands = $ray ;
    }

    protected function initialiseEnterprise() {
        $this->username = $this->askForPharaohEnterpriseUsername();
        $this->apiKey = $this->askForPharaohEnterpriseAPIKey();
    }

    protected function askForPharaohEnterpriseAPIKey(){
        if (isset($this->params["pharaoh-enterprise-api-key"])) { return $this->params["pharaoh-enterprise-api-key"] ; }
        $papyrusVar = \Model\AppConfig::getAppVariable("pharaoh-enterprise-api-key") ;
        if ($papyrusVar != null) {
            if (isset($this->params["guess"])) { return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getAppVariable("pharaoh-enterprise-api-key") ;
        if ($appVar != null) {
            $question = 'Use Application saved Pharaoh Enterprise API Key?';
            if (self::askYesOrNo($question, true) == true) { return $appVar ; } }
        $question = 'Enter Pharaoh Enterprise API Key';
        return self::askForInput($question, true);
    }

    protected function askForPharaohEnterpriseUsername(){
        if (isset($this->params["pharaoh-enterprise-user-name"])) { return $this->params["pharaoh-enterprise-user-name"] ; }
        $papyrusVar = \Model\AppConfig::getAppVariable("pharaoh-enterprise-user-name") ;
        if ($papyrusVar != null) {
            if ($this->params["guess"] == true) { return $papyrusVar ; } }
        $appVar = \Model\AppConfig::getAppVariable("pharaoh-enterprise-user-name") ;
        if ($appVar != null) {
            $question = 'Use Application saved Pharaoh Enterprise User Name?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        $question = 'Enter Pharaoh Enterprise User Name';
        return self::askForInput($question, true);
    }

    protected function installEnterprise() {

        // LDAP variables
        $pharaoh_auth_host = "directory.pharaohtools.com";  // your ldap servers
        $pharaoh_auth_port = "389";                 // your ldap server's port number
//        $pharaoh_auth_user = 'testuser@pharaohtools.com' ;
//        $pharaoh_auth_api_key = 'pharaoh12345678' ;
        $ptc_enterprise_repo = 'git@scmx.pharaohtools.com:pharaohtools/ptconfigure-enterprise.git' ;

        // Connecting to LDAP
        $ldapconn = ldap_connect($pharaoh_auth_host, $pharaoh_auth_port)  ;

        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

        if ($ldapconn) {
            echo "\n\nldap connected\n\n" ;
            $step_two_bind_output = $this->step_two_attempt_bind($ldapconn) ;
            if ($step_two_bind_output==false) {
                exit(1) ; }
            $step_three_user_output = $this->step_three_get_server_userfields($ldapconn) ;
            if ($step_three_user_output==false){
                exit(1) ; }
            $step_four_ssh_key_fields = $this->step_four_get_ssh_key_fields($step_three_user_output) ;
            if ($step_four_ssh_key_fields==false){
                exit(1) ; }
            $step_five = $this->step_five_install_ssh_keys($step_four_ssh_key_fields) ;
            if ($step_five==false){
                exit(1) ; }
            $step_six_enterprise_remote_result = $this->step_six_add_enterprise_remote($step_three_user_output) ;
            if ($step_six_enterprise_remote_result==false){
                exit(1) ; }
            $step_seven_pull_enterprise_result = $this->step_seven_pull_enterprise() ;
            if ($step_seven_pull_enterprise_result==false){
                exit(1) ; }
            echo "You have successfully upgraded Pharaoh Configure from Open Source to Enterprise" ;
        } else{
            echo "\n\nCould not connect to $pharaoh_auth_host\n\n" ;
        }

    }

    public function step_two_attempt_bind($ldapconn) {
        if ($bind = ldap_bind($ldapconn, $this->get_bind_user(), $this->apiKey)) {
            // log them in!
            echo "\n\nlog in\n\n" ;
            return $bind ; }
        else {
            // error message
            echo "\n\ndont log in\n\n" ;
            return false ; }
    }

    public function step_three_get_server_userfields($ldapconn) {
        echo "\n\n\nin step three" ;
        $dn = $this->get_bind_user() ; //"cn=username,o=My Company, c=US"; //the object itself instead of the top search level as in ldap_search
        $filter="(objectclass=*)"; // this command requires some filter
        $justthese = array("uid", "displayname", "mail", "postaladdress", "street"); //the attributes to pull, which is much more efficient than pulling all attributes if you don't do this
        $sr=ldap_read($ldapconn, $dn, $filter, $justthese);
        $entry = ldap_get_entries($ldapconn, $sr);
        $newuser["uid"] = $entry[0]["uid"][0] ;
        $newuser["displayname"] = $entry[0]["displayname"][0] ;
        $newuser["mail"] = $entry[0]["mail"][0] ;
        $newuser["postaladdress"] = $entry[0]["postaladdress"][0] ;
        $newuser["street"] = $entry[0]["street"][0] ;
//    var_dump("uid", $newuser["uid"], "displayname",  $newuser["displayname"], "mail", $newuser["mail"]) ;
        return (!is_null($entry)) ? $newuser : false ;
    }

    public function step_four_get_ssh_key_fields($step_three_user_output) {
        echo "\n\n\nin step four" ;
        $key_fields = array() ;
        if (isset($step_three_user_output["postaladdress"]) &&
            !empty($step_three_user_output["postaladdress"]) ) {
            $key_fields["scm_ssh_key_private"] = $step_three_user_output["postaladdress"] ; }
        if (isset($step_three_user_output["street"]) &&
            !empty($step_three_user_output["street"]) ) {
            $key_fields["scm_ssh_key_public"] = $step_three_user_output["street"] ; }

        if (isset($key_fields["scm_ssh_key_private"]) &&
            isset($key_fields["scm_ssh_key_public"])) {
            return $key_fields ; }
        return false ;

    }


    public function step_five_install_ssh_keys($step_four_ssh_key_fields) {

        $ssh_dir = "/opt/ptconfigure/.ssh" ;
        if (!file_exists($ssh_dir) || !is_dir($ssh_dir)) {
            passthru("mkdir -p {$ssh_dir}") ; }

        $types = ["public", "private"] ;
        foreach ($types as $type) {
            $typeString = ucfirst($type) ;
            echo "Checking for existing {$typeString} key \n" ;
            if ($this->does_key_exist($type)) {
                echo "{$typeString} Enterprise Key exists in file system, checking validity \n" ;
                $key_local_data = file_get_contents($this->get_key_path($type)) ;
                if ($key_local_data == $step_four_ssh_key_fields["scm_ssh_key_{$type}"]) {
                    echo "{$typeString} Enterprise Key in file system is valid \n" ;
                    continue ; }
                else {
                    echo "{$typeString} Enterprise Key in file system is invalid - overwriting \n" ;
                    $res = $this->add_key_to_ptconfigure($type, $step_four_ssh_key_fields["scm_ssh_key_{$type}"]) ;
                    if ($res == true) { continue ; }
                    else { return false ;} } }
            else {
                echo "{$typeString} Enterprise Key does not exist in file system\n" ;
                $res = $this->add_key_to_ptconfigure($type, $step_four_ssh_key_fields["scm_ssh_key_{$type}"]) ;
                if ($res == true) { continue ; }
                else { return false ;} }  }

        $comm = "chmod 0700 /opt/ptconfigure/.ssh/enterprise_key*" ;
        passthru($comm, $return) ;

        return true ;
    }


    public function step_six_add_enterprise_remote($step_three_user_output) {
        $ptc_dir = '/opt/ptconfigure/ptconfigure/' ;
        echo "changing directory to {$ptc_dir} \n" ;
        chdir($ptc_dir) ;
        $comm = 'git config --global user.email "'.$step_three_user_output["mail"].'" ';
        passthru($comm) ;
        $comm = 'git config --global user.name "'.$step_three_user_output["displayname"].'"' ;
        passthru($comm) ;
        $comm = "git remote rm enterprise" ;
        passthru($comm) ;
        $comm = "git remote add enterprise git@scmx.pharaohtools.com:pharaohtools/ptconfigure-enterprise.git" ;
        passthru($comm, $return) ;
        return ($return==0) ? true : false ;
    }

    public function step_seven_pull_enterprise() {
        $ptc_dir = '/opt/ptconfigure/ptconfigure/' ;
        echo "changing directory to {$ptc_dir} \n" ;
        chdir($ptc_dir) ;
        $comm = "git-key-safe -i /opt/ptconfigure/.ssh/enterprise_key pull enterprise master" ;
        passthru($comm, $return) ;
        $comm = "git-key-safe -i /opt/ptconfigure/.ssh/enterprise_key fetch --all" ;
        passthru($comm, $return) ;
        $comm = "git-key-safe -i /opt/ptconfigure/.ssh/enterprise_key reset --hard origin/master" ;
        passthru($comm, $return) ;
        $comm = "git-key-safe -i /opt/ptconfigure/.ssh/enterprise_key pull enterprise master" ;
        passthru($comm, $return) ;
        return ($return==0) ? true : false ;
    }




    public function does_key_exist($type = "public") {
        $fullkey = $this->get_key_path($type) ;
        $exists = file_exists($fullkey) ;
        if ($exists) {
            echo "Key {$fullkey} exists \n" ;
            return true ; }
        echo "Key {$fullkey} does not exist \n" ;
        return false ;
    }

    public function get_key_path($type = "public") {
        $ssh_dir = '/opt/ptconfigure/.ssh/' ;
        $key = 'enterprise_key' ;
        if ($type=="public") { $key .= ".pub" ; }
        return $ssh_dir.$key ;
    }

    public function add_key_to_ptconfigure($type, $data) {
        $path = $this->get_key_path($type) ;
        return file_put_contents($path, $data) ;
    }

    public function format_username($mail) {
        $user = str_replace("@", "-", $mail) ;
        $user = str_replace(".", "_", $user) ;
        return $user ;
    }

    protected function get_bind_user() {
        $bind_user = 'cn='.$this->username.',cn=Gold,ou=EnterpriseUsers,dc=directory,dc=pharaohtools,dc=com' ;
        return $bind_user ;
    }

}