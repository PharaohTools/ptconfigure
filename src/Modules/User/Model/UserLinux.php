<?php

Namespace Model;

class UserLinux extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian", "Redhat") ;
    public $distros = array("Ubuntu", "Redhat", "CentOS") ;
    public $versions = array( "any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $userName ;
    protected $actionsToMethods =
        array(
            "create" => "performUserCreate",
            "remove" => "performUserRemove",
            "set-password" => "performUserSetPassword",
            "show-groups" => "performShowGroups",
            "add-to-group" => "performUserAddToGroup",
            "remove-from-group" => "performUserRemoveFromGroup",
            "exists" => "performUserExistenceCheck",
            "ensure-exists" => "performUserEnsureExistence",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "User";
        $this->programDataFolder = "";
        $this->programNameMachine = "user"; // command and app dir name
        $this->programNameFriendly = "!User!!"; // 12 chars
        $this->programNameInstaller = "User";
        $this->initialize();
    }

    public function performUserCreate() {
        $this->setUser();
        $result = $this->create();
        return $result ;
    }

    public function performUserSetPassword() {
        $this->setUser();
        $this->setPassword();
    }

    public function performUserRemove() {
        $this->setUser();
        $result = $this->remove();
        return $result ;
    }

    public function performShowGroups() {
        $this->setUser();
        $result = $this->getGroups();
        return $result ;
    }

    public function performUserAddToGroup() {
        $this->setUser();
        $result = $this->addToGroup();
        return $result ;
    }

    public function performUserRemoveFromGroup() {
        $this->setUser();
        $result = $this->removeFromGroup();
        return $result ;
    }

    public function performUserExistenceCheck() {
        $this->setUser();
        return $this->exists();
    }

    public function performUserEnsureExistence() {
        $this->setUser();
        if ($this->exists()==true) { return true ; }
        $result = $this->create();
        return $result ;
    }

    public function setUser($userName = null) {
        if (isset($userName)) { $this->userName = $userName; }
        else if (isset($this->params["username"])) { $this->userName = $this->params["username"]; }
        else { $this->userName = self::askForInput("Enter Username:", true); }
        return ;
    }

    public function getShell() {
        if (isset($this->params["shell"])) { $shell = $this->params["shell"]; }
        else if (isset($this->params["guess"])) { $shell = '/bin/bash' ; }
        else { $shell = self::askForInput("Enter User Shell:", true); }
        return $shell ;
    }

    public function getFullName() {
        if (isset($this->params["fullname"])) { $fullname = $this->params["fullname"]; }
        else if (isset($this->params["guess"])) { $fullname = $this->userName ; }
        else { $fullname = self::askForInput("Enter User Full Name:", true); }
        return $fullname ;
    }

    public function getHomeDirectory() {
        if (isset($this->params["home-dir"])) { $home_dir = $this->params["home-dir"]; }
        else if (isset($this->params["home-directory"])) { $home_dir = $this->params["home-directory"]; }
        else if (isset($this->params["guess"])) { $home_dir = DS.'home'.DS.$this->userName.DS ; }
        else { $home_dir = self::askForInput("Enter User Home Directory:", true); }
        return $home_dir ;
    }

    public function setPassword() {
        if (isset($this->params["new-password"])) {
            $pword = $this->params["new-password"]; }
        else {
            $pword = self::askForInput("Enter New Password:", true); }
        $command = SUDOPREFIX.' echo "'.$this->userName.':'.$pword.'"|chpasswd' ;
        $this->executeAndOutput($command) ;
        return ;
       
    }

    public function getHome() {
        $command = SUDOPREFIX." -u {$this->userName} echo \$HOME" ;
        $home = $this->executeAndLoad($command) ;
        return trim($home);
    }

/*    public function hasSshKey() {
        $key = $this->getPrivateKeyFilename();
        return file_exists($key) ;
    }

    public function giveSshKey($force = false) {
        $key = $this->getPrivateKeyFilename();
        if ($this->hasSshKey() && $force == false) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("User has SSH Key already and force not specified", $this->getModuleName()) ; }
        else {
            $command = "sudo -u {$this->userName} ssh-keygen -t rsa -N '' -f " . escapeshellarg($key);
            $this->executeAndOutput($command) ; }
    }

    public function getPrivateKeyFilename() {
        return "{$this->getHome()}/.ssh/id_rsa";
    }

    public function getPublicKeyFilename() {
        return "{$this->getHome()}/.ssh/id_rsa.pub";
    }

    public function getPublicKey() {
        return file_get_contents($this->getPublicKeyFilename());
    }*/

    public function ensurePresent() {
        $this->setUser();
        if(!$this->exists()) { $this->create(); }
        return $this;
    }

    public function exists() {
        $retCode = $this->executeAndGetReturnCode("id {$this->userName}") ;
        return ($retCode["rc"] == 0) ? true : false ;
    }

    public function create() {
        # useradd -m -d /home/ptbuild -s /bin/bash -c "The Pharaoh Build User" -U ptbuild
        $shell = $this->getShell();
        $shell_string = ($shell == "" || $shell == false) ? "" : "-s {$shell} " ;
        $fullName = $this->getFullName();
        $fn_string = ($fullName == "" || $fullName == false) ? "" : "-c {$fullName} " ;
        $directory = $this->getHomeDirectory();
//        $username = $this->getUsername();
        $dir_string = ($directory == "" || $directory == false) ? "" : "-d {$directory} " ;
        $ua_comm = "useradd -m {$dir_string}{$shell_string} -c \"{$fn_string}\" -U {$this->userName}" ;
//        $ua_comm = "adduser {$this->userName}" ;
        $retCode = $this->executeAndGetReturnCode($ua_comm) ;
        if ($retCode["rc"] !== 0) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("User Add command did not execute correctly", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ; }
        return true ;
    }

    public function remove($autopilot = null) {
        $command = "deluser {$this->userName}" ;
        $command .= (isset($this->params["user-force"]) || isset($autopilot["user-force"])) ? " --force" : "" ;
        $command .= (isset($this->params["remove"]) || isset($autopilot["remove"])) ? " --remove" : "" ;
        $retCode = $this->executeAndGetReturnCode($command) ;
        if ($retCode !== 0) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("User Delete command did not execute correctly", $this->getModuleName()) ;
            return false ; }
        return true ;
    }

    public function ensureInGroup($groupName) {
        if(!$this->inGroup($groupName)) {
            $this->addToGroup($groupName); }
        return $this;
    }

    public function inGroup($groupName) {
        $groups = $this->getGroups();
        return in_array($groupName, $groups);
    }

    public function getGroups() {
        return $this->executeAndLoad("id -Gn {$this->userName}") ;
    }

    private function addToGroup($groupName = null) {
        if (isset($groupName) ) { }
        else if (isset($this->params["group-name"])) {
            $groupName = $this->params["group-name"]; }
        else if (isset($this->params["groupname"])) {
            $groupName = $this->params["groupname"]; }
        else if (isset($this->params["group"])) {
            $groupName = $this->params["group"]; }
        else {
            $groupName = self::askForInput("Enter Group Name:", true); }
        $returnCode = $this->executeAndGetReturnCode("usermod -aG {$groupName} {$this->userName}");
        if ($returnCode !== 0) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Adding User {$this->userName} to the Group {$groupName} did not execute correctly", $this->getModuleName()) ;
            return false ; }
        return true ;
    }

    private function removeFromGroup($groupName = null) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (isset($groupName) ) { }
        else if (isset($this->params["group-name"])) {
            $groupName = $this->params["group-name"]; }
        else if (isset($this->params["groupname"])) {
            $groupName = $this->params["groupname"]; }
        else if (isset($this->params["group"])) {
            $groupName = $this->params["group"]; }
        else {
            $groupName = self::askForInput("Enter Group Name:", true); }
        if ($groupName == "") {
            $logging->log("Group name cannot be empty", $this->getModuleName()) ;
            return false ; }
        $returnCode = $this->executeAndGetReturnCode("deluser {$this->userName} {$groupName}");
        if ($returnCode !== 0) {
            $logging->log("Removing User {$this->userName} from the Group {$groupName} did not execute correctly", $this->getModuleName()) ;
            return false ; }
        return true ;
    }

}
