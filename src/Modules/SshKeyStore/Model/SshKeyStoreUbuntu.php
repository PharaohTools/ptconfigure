<?php

Namespace Model;

class SshKeyStoreUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    protected $key ;
    protected $userName ;
    protected $userHomeDir ;
    protected $searchLocations;
    protected $actionsToMethods = array("find" => "findKey") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "SshKeyStore";
        $this->programDataFolder = "";
        $this->programNameMachine = "sshkeystore"; // command and app dir name
        $this->programNameFriendly = "SshKey Store"; // 12 chars
        $this->programNameInstaller = "Ssh Key Store";
        $this->initialize();
    }

    protected function askPublicKeyInstall() {
        $doPubKeyInstall = (isset($this->params["yes"]) && $this->params["yes"]==true) ?
            true : $this->askWhetherToInstallLinuxAppToScreen();
        if ($doPubKeyInstall == true) { return $this->installPublicKey(); }
        return false ;
    }

    protected function askWhetherToInstallPublicKeyToScreen(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = "Find SSH Public Key?";
        return self::askYesOrNo($question);
    }

    public function findKey() {

        $this->setKeyName() ;
        $this->setSearchLocations() ;
        $this->setPreferredLocation() ;
        return $this->doLocationSearch() ;

        // if isset prefer, put that array entry first
        // foreach location check for keyfile. if found, return it
    }

    protected function setSearchLocations() {
        if (isset($this->params["locations"])) { $searchLocations = explode(",", $this->params["locations"]) ; }
        else { $searchLocations = array("user", "otheruser", "root", "specify") ; }
        $this->searchLocations = $searchLocations ;
    }

    protected function setPreferredLocation() {
        if (isset($this->params["prefer"])) {
            foreach ($this->searchLocations as &$loc) {
                if ($this->params["prefer"] == $loc) {
                    unset ($loc) ;
                    $this->searchLocations = array_merge(array($this->params["prefer"]), $this->searchLocations) ;
                    return ; } } }
    }

    protected function setKeyName() {
        if (isset($this->params["key"])) {
            if (substr($this->params["key"], 0, 4) == 'KS::') {$this->params["key"] = substr($this->params["key"], 4) ; }
            $this->key = $this->params["key"]; }
        else {
            $question = "Enter SSH Key Name:";
            $this->key = self::askForInput($question, true); }
    }

    protected function setUserHome() {
        if (isset($this->userName)) {
            // @todo a check if the user exists
            // @todo there is no user module
            $userFactory = new \Model\User() ;
            $user = $userFactory->getModel($this->params);
            $user->setUser($this->userName) ;
            $this->userHomeDir = $user->getHome(); }
        else if (isset($this->params["user-home"])) {
            $this->userHomeDir = $this->params["user-home"]; }
        else if (isset($this->params["guess"])) {
            $whoami = self::executeAndLoad("whoami") ;
            $whoami = str_replace("\n", "", $whoami) ;
            $whoami = str_replace("\r", "", $whoami) ;
            $this->userHomeDir = '/home/'.$whoami; }
        else {
            $question = "Enter User home directory:";
            $this->userHomeDir = self::askForInput($question, true); }
    }

    protected function doLocationSearch() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Trying keystore keys");
        foreach ($this->searchLocations as &$loc) {
            switch ($loc) {
                case "user" :
                    $this->setUserHome() ;
                    $kp = $this->userHomeDir.DS.".ssh".DS.$this->key ;
                    if (file_exists($kp)) {
                        $logging->log("User key found at $kp");
                        return $kp ; }
                    else {
                        $logging->log("User key not found at $kp"); }
                    break ;
                case "otheruser" :
                    $this->userName = (isset($this->params["otheruser"])) ? $this->params["otheruser"] : null ;
                    $this->setUserHome() ;
                    $kp = $this->userHomeDir.DS.".ssh".DS.$this->key ;
                    if (file_exists($kp)) {
                        $logging->log("Other User key found at $kp");
                        return $kp ; }
                    else {
                        $logging->log("Other User key not found at $kp"); }
                    break ;
                case "root" :
                    $kp = "/root/.ssh".DS.$this->key ;
                    if (file_exists($kp)) {
                        $logging->log("Root key found at $kp");
                        return $kp ; }
                    else {
                        $logging->log("Root key not found at $kp"); }
                    break ;
                    break ;
                case "specify" :
                    break ;
            }
        }
        return null;
    }

    /*
     * [3]=>
  array(3) {
    ["any-app"]=>
    array(2) {
      ["gen_env_name"]=>
      string(19) "shared-temp-staging"
      ["gen_env_tmp_dir"]=>
      string(5) "/tmp/"
    }
    ["servers"]=>
    array(1) {
      [0]=>
      array(6) {
        ["target"]=>
        string(12) "178.62.10.22"
        ["user"]=>
        string(4) "root"
        ["password"]=>
        string(17) "/root/.ssh/id_rsa"

     */

}