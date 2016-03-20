<?php

Namespace Model;

class SudoNoPassLinuxMac extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "SudoNoPass";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "askForInstallUserName", "params" => array() ) ) ,
            array("method"=> array("object" => $this, "method" => "displayDangerousFileWarning", "params" => array() ) ) ,
            array("method"=> array("object" => $this, "method" => "performFileModification", "params" => array() ) ) ,
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "askForInstallUserName", "params" => array() ) ) ,);
        $this->programDataFolder = "";
        $this->programNameMachine = "sudonopass"; // command and app dir name
        $this->programNameFriendly = "Sudo NoPass!"; // 12 chars
        $this->programNameInstaller = "Sudo w/o Pass for User";
        $this->initialize();
    }

    public function displayDangerousFileWarning() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("The following will be written to /etc/sudoers", $this->getModuleName()) ;
        $logging->log("Please check if it looks wrong", $this->getModuleName()) ;
        $logging->log("You may not be able to use Sudo if it is incorrect!!!", $this->getModuleName()) ;
        $logging->log("$this->installUserName ALL=NOPASSWD: ALL", $this->getModuleName()) ;
        if ( isset($this->params["yes"]) && $this->params["yes"]==true) {
            $this->params["perform-file-modifications"] = true ; }
        else {
            $question = 'Is this okay?';
            $this->params["perform-file-modifications"] = self::askYesOrNo($question, true); }
    }

    public function performFileModification() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("About to perform File Modifications on /etc/sudoers", $this->getModuleName()) ;
        if ($this->params["perform-file-modifications"] == true ) {
            $line = $this->installUserName.' ALL=NOPASSWD: ALL' ;
            $fileFactory = new \Model\File();
            $params = $this->params ;
            $params["file"] = "/etc/sudoers" ;
            $params["search"] = $line ;
            $file = $fileFactory->getModel($params) ;
            $res[] = $file->performShouldHaveLine();
            return in_array(false, $res)==false ; }
        return true ;
    }

}