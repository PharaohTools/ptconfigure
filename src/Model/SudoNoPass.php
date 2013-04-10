<?php

Namespace Model;

class SudoNoPass extends BaseLinuxApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "SudoNoPass";
    $this->installCommands = array();
    $this->uninstallCommands = array( "" );
    $this->programDataFolder = "";
    $this->programNameMachine = "sudonopass"; // command and app dir name
    $this->programNameFriendly = "Sudo NoPass!"; // 12 chars
    $this->programNameInstaller = "Sudo w/o Pass for User";
    $this->registeredPreInstallFunctions = array("askForInstallUserName",
        "setInstallCommandsWithNewUserName");
    $this->registeredPreUnInstallFunctions = array("askForInstallUserName");
    $this->initialize();
  }

  protected function setInstallCommandsWithNewUserName() {
      $this->installCommands = array(
        'echo "The following will be written to /etc/sudoers" ',
        'echo "Please check if it looks wrong" ',
        'echo "It may break your system if wrong !!!" ',
        'echo "'.$this->installUserName.' ALL=NOPASSWD: ALL" ',
        'echo "'.$this->installUserName.' ALL=NOPASSWD: ALL" >> /etc/sudoers '
    );
  }

}