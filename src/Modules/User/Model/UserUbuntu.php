<?php

Namespace Model;

class UserUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
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

    // @todo refactor this 14 lines of ugliness
    public function askWhetherToDoAction($action) {
        if ( isset($this->actionsToMethods)) {
            if (method_exists($this, $this->actionsToMethods[$action])) {
                $return = $this->{$this->actionsToMethods[$action]}() ;
                return $return ; }
            else {
                $consoleFactory = new \Model\Console();
                $console = $consoleFactory->getModel($this->params);
                $console->log("No method {$this->actionsToMethods[$action]} in model ".get_class($this)) ;
                return false; } }
        else {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log('No property $actionsToMethods in model '.get_class($this)) ;
            return false; }
    }

    protected function performUserCreate() {
        $this->setUser();
        return $this->create();
    }

    protected function performUserSetPassword() {
        $this->setUser();
        $this->setPassword();
    }

    protected function performUserRemove() {
        $this->setUser();
        $result = $this->remove();
        return $result ;
    }

    protected function performShowGroups() {
        $this->setUser();
        $result = $this->getGroups();
        return $result ;
    }

    protected function performUserAddToGroup() {
        $this->setUser();
        $result = $this->addToGroup();
        return $result ;
    }

    protected function performUserRemoveFromGroup() {
        $this->setUser();
        $result = $this->removeFromGroup();
        return $result ;
    }

    protected function performUserExistenceCheck() {
        $this->setUser();
        return $this->exists();
    }

    public function setUser($userName = null) {
        if (isset($userName)) {
            $this->userName = $userName; }
        else if (isset($this->params["username"])) {
            $this->userName = $this->params["username"]; }
        else if (isset($autopilot["username"])) {
            $this->userName = $autopilot["username"]; }
        else {
            $this->userName = self::askForInput("Enter Username:", true); }
    }

    public function setPassword($autopilot = null) {
        if (isset($this->params["new-password"])) {
            $pword = $this->params["new-password"]; }
        else if (isset($autopilot["new-password"])) {
            $pword = $autopilot["new-password"]; }
        else {
            $pword = self::askForInput("Enter New Password:", true); }
        $command = 'sudo echo "'.$this->userName.':'.$pword.'"|chpasswd' ;
        $this->executeAndOutput($command) ;
    }

    public function getHome() {
        $command = "sudo -u {$this->userName} echo \$HOME" ;
        $home = $this->executeAndLoad($command) ;
        return trim($home);
    }

    public function hasSshKey() {
        $key = $this->getPrivateKeyFilename();
        return file_exists($key) ;
    }

    public function giveSshKey($autopilot = null, $force = false) {
        $key = $this->getPrivateKeyFilename();
        if (is_null($autopilot) && $this->hasSshKey() && $force == false) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("User has SSH Key already and force not specified") ; }
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
    }

    public function ensurePresent() {
        if(!$this->exists()) { $this->create(); }
        return $this;
    }

    public function exists() {
        $retCode = $this->executeAndGetReturnCode("id {$this->userName} >/dev/null 2>&1") ;
        return ($retCode == 0) ? true : false ;
    }

    public function create() {
        $retCode = $this->executeAndGetReturnCode("useradd {$this->userName} -m") ;
        if ($retCode == 1) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("User Add command did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function remove($autopilot = null) {
        $command = "userdel {$this->userName}" ;
        $command .= (isset($this->params["user-force"]) || isset($autopilot["user-force"])) ? " --force" : "" ;
        $command .= (isset($this->params["remove"]) || isset($autopilot["remove"])) ? " --remove" : "" ;
        $retCode = $this->executeAndGetReturnCode($command) ;
        if ($retCode !== 0) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("User Delete command did not execute correctly") ;
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

    private function addToGroup($autopilot = null, $groupName = null) {
        if (isset($groupName) ) { }
        else if (isset($this->params["group-name"])) {
            $groupName = $this->params["group-name"]; }
        else if (isset($autopilot["group-name"])) {
            $groupName = $autopilot["group-name"]; }
        else {
            $groupName = self::askForInput("Enter New Password:", true); }
        $returnCode = $this->executeAndGetReturnCode("usermod -aG {$groupName} {$this->userName}");
        if ($returnCode !== 0) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Adding User {$this->userName} to the Group {$groupName} did not execute correctly") ;
            return false ; }
        return true ;
    }

    private function removeFromGroup($autopilot = null, $groupName = null) {
        if (isset($groupName) ) { }
        else if (isset($this->params["group-name"])) {
            $groupName = $this->params["group-name"]; }
        else if (isset($autopilot["group-name"])) {
            $groupName = $autopilot["group-name"]; }
        else {
            $groupName = self::askForInput("Enter New Password:", true); }
        $returnCode = $this->executeAndGetReturnCode("deluser {$this->userName} {$groupName}");
        if ($returnCode !== 0) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Removing User {$this->userName} from the Group {$groupName} did not execute correctly") ;
            return false ; }
        return true ;
    }

}