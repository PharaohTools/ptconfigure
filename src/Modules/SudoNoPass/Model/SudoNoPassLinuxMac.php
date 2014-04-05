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
            array("command"=> $this->setInstallCommandsWithNewUserName() , )
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "askForInstallUserName", "params" => array() ) ) ,);
        $this->programDataFolder = "";
        $this->programNameMachine = "sudonopass"; // command and app dir name
        $this->programNameFriendly = "Sudo NoPass!"; // 12 chars
        $this->programNameInstaller = "Sudo w/o Pass for User";
        $this->initialize();
    }

    public function setInstallCommandsWithNewUserName() {
        return array(
            'echo "The following will be written to /etc/sudoers" ',
            'echo "Please check if it looks wrong" ',
            'echo "It may break your system if wrong !!!" ',
            'echo "'.$this->installUserName.' ALL=NOPASSWD: ALL" ',
            'echo "'.$this->installUserName.' ALL=NOPASSWD: ALL" >> /etc/sudoers '
        );
    }

}