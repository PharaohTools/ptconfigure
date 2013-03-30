<?php

Namespace Model;

class Firefox14 extends BaseLinuxApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "Firefox14";
    $this->installCommands = array(
      "cd /tmp" ,
      "git clone https://github.com/phpengine/boxboss-firefox14 firefox14",
      "rm -rf ****PROGDIR****",
      "mkdir -p ****PROGDIR****",
      "mv /tmp/firefox14/* ****PROGDIR****",
      "rm -rf /tmp/firefox14" );
    $this->uninstallCommands = array(
      "rm -rf ****PROGDIR****",
      "rm -rf ****PROG EXECUTOR****", );
    $this->programDataFolder = "/opt/firefox14"; // command and app dir name
    $this->programNameMachine = "firefox14"; // command and app dir name
    $this->programNameFriendly = " Firefox 14 "; // 12 chars
    $this->programNameInstaller = "Firefox 14";
    $this->programExecutorFolder = "/usr/bin";
    $this->programExecutorTargetPath = "firefox-bin";
    $this->programExecutorCommand = $this->programDataFolder.'/'.$this->programExecutorTargetPath;
    $this->registeredPostInstallFunctions = array("deleteExecutorIfExists",
      "saveExecutorFile");
    $this->initialize();
  }

}