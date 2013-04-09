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

  private function setInstallCommandsWithNewUserName() {
    array('echo "'.$this->installUserName.' ALL=NOPASSWD: ALL" >> /etc/sudoers ' );
  }

}