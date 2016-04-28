<?php

Namespace Model;

class SshKeyPrivateInstallLinux extends SshKeyInstallLinux {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Private") ;

    protected $privateKey ;
    protected $keyName ;
    protected $actionsToMethods = array(
        "private-key" => "askPrivateKeyInstall",
        "private" => "askPrivateKeyInstall",
    ) ;

    protected function askPrivateKeyInstall() {
        $doPubKeyInstall = (isset($this->params["yes"]) && $this->params["yes"]==true) ?
            true : $this->askWhetherToInstallLinuxAppToScreen();
        if ($doPubKeyInstall == true) { return $this->installPrivateKey(); }
        return false ;
    }

    protected function askWhetherToInstallPrivateKeyToScreen(){
        $question = "Install SSH Private Key?";
        return self::askYesOrNo($question);
    }

    public function installPrivateKey() {
        $this->setUsername() ;
        $this->setUserHome() ;
        $this->ensureSSHDir() ;
//        $this->ensureAuthUserFile() ;
        if ($this->setKey() == false) { return false ; }
        if ($this->ensureKeyInstalled() == false) { return false ; }
        $this->restartService() ;
        return true ;
    }

    protected function setKey() {
        $loggingFactory = new \Model\Logging() ;
        $logging = $loggingFactory->getModel($this->params);
        if (isset($this->params["private-key"])) {
            if (file_exists($this->params["private-key"])) {
                $this->privateKey = file_get_contents($this->params["private-key"]) ;}
            else {
                $logging->log("Unable to use the requested Private Key from {$this->params["private-key"]}", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                return false ; } }
        else if (isset($this->params["private-key-file"])) {
            // $this->privateKey = file_get_contents($this->params["private-key-file"]) ;
            if (file_exists($this->params["private-key-file"])) {
                $this->privateKey = file_get_contents($this->params["private-key-file"]) ;}
            else {
                $logging->log("Unable to find the requested Private Key from {$this->params["private-key-file"]}", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                return false ; } }
        else if (isset($this->params["private-key-data"])) {
            $this->privateKey = $this->params["private-key-data"] ; }
        else {
            $question = "Enter Private Key:";
            $this->privateKey = self::askForInput($question, true); }
    }

    protected function ensureKeyInstalled() {
        $keyFile = $this->userHomeDir.DS.'.ssh'.DS.$this->keyName ;
        $loggingFactory = new \Model\Logging() ;
        $logging = $loggingFactory->getModel($this->params);
        if ($this->privateKey === false || (is_string($this->privateKey) && strlen($this->privateKey)<1) ) {
            $logging->log("Unable to use this Private Key", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            $logging->log("{$this->privateKey}", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ; }
        $fileFactory = new \Model\File() ;
        $params = $this->params ;
        $params["file"] = $keyFile ;
        $params["search"] = $this->privateKey ;
        $file = $fileFactory->getModel($params) ;
        $res = array() ;
        $res[] = $file->shouldExist();
        $res[] = $file->performShouldHaveLine();
        $this->setOwnership($keyFile) ;
        return !in_array(false, $res) ;
    }

    protected function setKeyName() {
        if (isset($this->params["key-name"])) {
            $this->keyName = $this->params["key-name"] ; }
        else if (isset($this->params["name"])) {
            $this->keyName = $this->params["name"] ; }
        else {
            $question = "Enter Key Name:";
            $this->keyName = self::askForInput($question, true); }
    }

}
