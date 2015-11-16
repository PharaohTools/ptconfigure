<?php

Namespace Model;

class PharaohEnterpriseTestCredentials extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("TestCredentials") ;

    protected $username ;
    protected $apiKey ;

    public function __construct($params) {
        parent::__construct($params);
        $this->programNameMachine = "PharaohEnterprise"; // command and app dir name
        $this->programNameFriendly = "PT Enterprise"; // 12 chars
        $this->programNameInstaller = "Pharaoh Enterprise - upgrade from open source to Enterprise";
        $this->statusCommand = "httpd -v" ;
        $this->versionInstalledCommand = SUDOPREFIX.'git log -n 1 --pretty=format:"%H"' ;
        $this->versionRecommendedCommand = SUDOPREFIX.'git log -n 1 --pretty=format:"%H"' ;
        $this->versionLatestCommand = SUDOPREFIX.'git log -n 1 --pretty=format:"%H"' ;
        $this->initialize();
    }

    public function setInstallCommands() {
        $ray =
            array(
                array("method"=> array("object" => $this, "method" => "initialiseEnterprise", "params" => array()) ),
                array("method"=> array("object" => $this, "method" => "testCredentials", "params" => array()) ),
                array("method"=> array("object" => $this, "method" => "saveSuccess", "params" => array()) ),
            ) ;
        $this->installCommands = $ray ;
    }

    protected function initialiseEnterprise() {
        $this->username = $this->askForPharaohEnterpriseUsername();
        $this->apiKey = $this->askForPharaohEnterpriseAPIKey();
    }

    protected function askForPharaohEnterpriseAPIKey(){
        if (isset($this->params["api-key"])) { return $this->params["api-key"] ; }
        $appVar = \Model\AppConfig::getAppVariable("pharaoh-enterprise-api-key") ;
        if ($appVar != null) {
            $question = 'Use Application saved Pharaoh Enterprise API Key?';
            if (self::askYesOrNo($question, true) == true) { return $appVar ; } }
        $question = 'Enter Pharaoh Enterprise API Key';
        return self::askForInput($question, true);
    }

    protected function askForPharaohEnterpriseUsername(){
        if (isset($this->params["user-name"])) { return $this->params["user-name"] ; }
        $appVar = \Model\AppConfig::getAppVariable("pharaoh-enterprise-user-name") ;
        if ($appVar != null) {
            $question = 'Use Application saved Pharaoh Enterprise User Name?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        $question = 'Enter Pharaoh Enterprise User Name';
        return self::askForInput($question, true);
    }

    protected function testCredentials() {

        $pharaoh_auth_host = "directory.pharaohtools.com";
        $pharaoh_auth_port = "389";                 // your ldap server's port number

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Attempting Pharaoh Enterprise installation...", $this->getModuleName()) ;

        $ldapconn = ldap_connect($pharaoh_auth_host, $pharaoh_auth_port)  ;
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

        if ($ldapconn) {
            $logging->log("Authentication Server Connected...", $this->getModuleName()) ;
            $step_two_bind_output = $this->step_two_attempt_bind($ldapconn) ;
            if ($step_two_bind_output==false) { return false ; }
            $step_three_user_output = $this->step_three_get_server_userfields($ldapconn) ;
            if ($step_three_user_output==false){ return false ; }
            $scm_auth_res = $this->test_scmx_authentication($step_three_user_output) ;
            if ($scm_auth_res==false){ return false ; }
            $logging->log("You have successfully authenticated with an Enterprise server", $this->getModuleName()) ;
            return true ;}
        else {
            $logging->log("Connection to {$pharaoh_auth_host}:{$pharaoh_auth_port} failed...", $this->getModuleName()) ;
            return false ; }

    }

    public function saveSuccess() {
        if ($this->params["save-success"]==true) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Storing Credentials after successful authentication...", $this->getModuleName()) ;
//            $saverFactory = new \Model\PharaohEnterprise() ;
//            $saver = $saverFactory->getModel($this->params, "SaveCredentials") ;
//            $res = $saver->saveCredentials() ;
//            return $res ;
            return true ;
        }
        return true ;
    }

    protected function saveCredentials() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Storing Pharaoh Enterprise credentials...", $this->getModuleName()) ;
        \Model\AppConfig::setAppVariable("pharaoh-enterprise-user-name", $this->username);
        \Model\AppConfig::setAppVariable("pharaoh-enterprise-api-key", $this->apiKey) ;
        return true ;
    }

    public function step_two_attempt_bind($ldapconn) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ($bind = ldap_bind($ldapconn, $this->get_bind_user(), $this->apiKey)) {
            $logging->log("Authentication server login successful...", $this->getModuleName()) ;
            return $bind ; }
        else {
            $logging->log("Authentication server login failed...", $this->getModuleName()) ;
            return false ; }
    }

    public function step_three_get_server_userfields($ldapconn) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Loading Account Information...", $this->getModuleName()) ;
        $dn = $this->get_bind_user() ; //"cn=username,o=My Company, c=US"; //the object itself instead of the top search level as in ldap_search
        $filter="(objectclass=*)"; // this command requires some filter
        $justthese = array("uid", "displayname", "mail", "postaladdress", "street"); //the attributes to pull, which is much more efficient than pulling all attributes if you don't do this
        $sr=ldap_read($ldapconn, $dn, $filter, $justthese);
        $entry = ldap_get_entries($ldapconn, $sr);
        if (count($entry)==0) {
            $logging->log("Unable to authenticate your user credentials...", $this->getModuleName()) ;
            return false ; }
        $newuser["uid"] = $entry[0]["uid"][0] ;
        $newuser["displayname"] = $entry[0]["displayname"][0] ;
        $newuser["mail"] = $entry[0]["mail"][0] ;
        $newuser["postaladdress"] = $entry[0]["postaladdress"][0] ;
        $newuser["street"] = $entry[0]["street"][0] ;
        $logging->log("Great, authenticated you as ...", $this->getModuleName()) ;
        $logging->log("    {$newuser["displayname"]}...", $this->getModuleName()) ;
        $logging->log("    {$newuser["mail"]}...", $this->getModuleName()) ;
        return $newuser ;
    }

    public function test_scmx_authentication($user) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $ptc_dir = '/tmp/test_pharaoh_auth' ;
        $ssh_dir = '/tmp/test_pharaoh_auth/.ssh' ;
        $logging->log("Making ssh dir", $this->getModuleName()) ;
        mkdir($ssh_dir, 0777, true) ;
        $logging->log("Making ssh dir", $this->getModuleName()) ;
        file_put_contents($ssh_dir."/enterprise_key", $user["postaladdress"]) ;
        file_put_contents($ssh_dir."/enterprise_key.pub", $user["street"]) ;
        $comm = "chmod 0700 {$ssh_dir}/enterprise_key*" ;
        $this->executeAndOutput($comm) ;
        $logging->log("Changing directory to {$ptc_dir}...", $this->getModuleName()) ;
        chdir($ptc_dir) ;
        $logging->log("Configuring Source Control...", $this->getModuleName()) ;
        $comm = 'git config --global user.email "'.$user["mail"].'" ';
        $this->executeAndOutput($comm) ;
        $comm = 'git config --global user.name "'.$user["displayname"].'"' ;
        $this->executeAndOutput($comm) ;
        $comm = "git init" ;
        $this->executeAndOutput($comm) ;
        $comm = "git remote rm enterprise_test" ;
        $this->executeAndOutput($comm) ;
        $comm = "git remote add enterprise_test git@scmx.pharaohtools.com:pharaohtools/ptconfigure-enterprise.git" ;
        $this->executeAndOutput($comm) ;
        $comm = "git-key-safe -i {$ssh_dir}/enterprise_key remote show enterprise_test" ;
        $this->executeAndOutput($comm) ;
        $comm = "rm -rf {$ptc_dir}" ;
        $this->executeAndOutput($comm) ;
        return true ;
    }


    public function does_key_exist($type = "public") {
        $fullkey = $this->get_key_path($type) ;
        $exists = file_exists($fullkey) ;
        if ($exists) { return true ; }
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