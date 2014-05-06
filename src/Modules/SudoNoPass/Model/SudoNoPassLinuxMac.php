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
        echo "The following will be written to /etc/sudoers\n" ;
        echo "Please check if it looks wrong\n" ;
        echo "You may not be able to use Sudo if it is incorrect!!!\n" ;
        echo "$this->installUserName ALL=NOPASSWD: ALL\n" ;
        if ( isset($this->params["yes"]) && $this->params["yes"]==true) {
            $this->params["perform-file-modifications"] = true ; }
        else {
            $question = 'Is this okay?';
            $this->params["perform-file-modifications"] = self::askYesOrNo($question, true); }
    }

    public function performFileModification() {
        if ($this->params["perform-file-modifications"] == true ) {
            $this->executeAndOutput('echo "'.$this->installUserName.' ALL=NOPASSWD: ALL" >> /etc/sudoers ' ) ; }
    }

}