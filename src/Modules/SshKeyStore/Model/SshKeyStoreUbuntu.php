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

    protected $userName ;
    protected $userHomeDir ;
    protected $publicKey ;
    protected $actionsToMethods = array("find-key-path" => "askPrivateKeyName") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "SshKeyStore";
        $this->programDataFolder = "";
        $this->programNameMachine = "sshkeyinstall"; // command and app dir name
        $this->programNameFriendly = "!SshKey Inst!"; // 12 chars
        $this->programNameInstaller = "Ssh Key Install";
        $this->initialize();
    }

    protected function askPublicKeyInstall() {
        $doPubKeyInstall = (isset($this->params["yes"]) && $this->params["yes"]==true) ?
            true : $this->askWhetherToInstallLinuxAppToScreen();
        if ($doPubKeyInstall == true) { return $this->installPublicKey(); }
        return false ;
    }

    protected function askWhetherToInstallPublicKeyToScreen(){
        $question = "Install SSH Public Key?";
        return self::askYesOrNo($question);
    }

    public function installPublicKey() {
        $this->setUsername() ;
        $this->setUserHome() ;
        $this->ensureSSHDir() ;
        $this->ensureAuthUserFile() ;
        $this->setKey();
        $this->ensureKeyInstalled() ;
        $this->restartService() ;
        return true ;
    }

    protected function setUsername() {
        if (isset($this->params["username"])) {
            $this->userName = $this->params["username"]; }
        else if (isset($this->params["user-name"])) {
            $this->params["username"] = $this->params["user-name"] ;
            $this->userName = $this->params["user-name"]; }
        else {
            $question = "Enter Username to install SSH Key to:";
            $this->userName = self::askForInput($question, true); }
    }

    protected function setUserHome() {
        if (isset($this->userName)) {
            //@todo a check if the user exists
            $userFactory = new \Model\User() ;
            $user = $userFactory->getModel($this->params);
            $user->setUser($this->userName) ;
            $this->userHomeDir = $user->getHome(); }
        else if (isset($this->params["user-home"])) {
            $this->userHomeDir = $this->params["user-home"]; }
        else if (isset($this->params["guess"])) {
            $this->userHomeDir = '/home/'.$this->params["username"]; }
        else {
            $question = "Enter User home directory:";
            $this->userHomeDir = self::askForInput($question, true); }
    }

    protected function ensureSSHDir() {
        $consoleFactory = new \Model\Console() ;
        $console = $consoleFactory->getModel($this->params);
        $sshDir = $this->userHomeDir.'/.ssh' ;
        if (file_exists($sshDir)) {
            $console->log("SSH Directory exists, so not creating.") ; }
        else {
            $console->log("SSH Directory does not exist, so creating.") ;
            // @todo do a chown after?
            $this->setOwnership($sshDir); }
    }

    protected function ensureAuthUserFile() {
        $consoleFactory = new \Model\Console() ;
        $console = $consoleFactory->getModel($this->params);
        $authFile = $this->userHomeDir.'/.ssh/authorized_keys' ;
        if (file_exists($authFile)) {
            $console->log("$authFile exists, so not creating.") ; }
        else {
            $console->log("$authFile does not exist, so creating.") ;
            touch($authFile);
            // @todo do a chown after?
            $this->setOwnership($authFile); }
    }

    protected function setOwnership($file) {
        $consoleFactory = new \Model\Console() ;
        $console = $consoleFactory->getModel($this->params);
        if (!file_exists($file)) {
            $console->log("$file does not exist, so not changing ownership.") ; }
        else {
            $console->log("Changing ownership of $file to user {$this->user}.") ;
            chown($file, $this->userName); }
    }

    protected function setKey() {
        if (isset($this->params["public-key-file"]) && file_exists($this->params["public-key-file"])) {
            $this->publicKey = file_get_contents($this->params["public-key-file"]) ; }
        else if (isset($this->params["public-key-data"])) {
            $this->publicKey = $this->params["public-key-data"] ; }
        else {
            $question = "Enter Public Key:";
            $this->publicKey = self::askForInput($question, true); }
    }

    protected function ensureKeyInstalled() {
        $authFile = $this->userHomeDir.'/.ssh/authorized_keys' ;
        $keys = explode("\n", file_get_contents($authFile)) ;
        $consoleFactory = new \Model\Console() ;
        $console = $consoleFactory->getModel($this->params);
        foreach ($keys as $key) {
            if ($key == $this->publicKey) {
                $keyExists = true ;
                break ; } }
        if (isset($keyExists) && $keyExists == true) {
            $console->log("Key already exists, so not adding.") ; }
        else {
            $console->log("Key does not exist in file, so adding.") ;
            $keys[] = $this->publicKey."\n" ;
            $keyFileData = implode("\n", $keys) ;
            file_put_contents($authFile, $keyFileData) ;
            $this->setOwnership($authFile) ; }
    }

    // @todo will restarting ssh break it all?
    private function restartService() {
        $serviceFactory = new \Model\Service();
        $service = $serviceFactory->getModel($this->params);
        $service->setService("ssh") ;
        $service->restart() ;
    }

}