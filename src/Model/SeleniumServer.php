<?php

Namespace Model;

class SeleniumServer extends BaseLinuxApp {

  public function __construct() {

    parent::__construct();
    $this->autopilotDefiner = "SeleniumServer";
    $this->installCommands = array(
      "apt-get install -y git" ,
      "cd /tmp" ,
      "git clone https://github.com/phpengine/boxboss-selenium selenium",
      "mkdir -p ****PROGDIR****",
      "mv /tmp/selenium/* ****PROGDIR****",
      "cd ****PROGDIR****",
      "java selenium-server.jar' " );
    $this->uninstallCommands = array("rm -rf ****PROGDIR****");
    $this->programDataFolder = "";
    $this->programNameMachine = "selenium"; // command and app dir name
    $this->programNameFriendly = "Selenium Srv"; // 12 chars
    $this->programNameInstaller = "Selenium Server";
    $this->registeredPreInstallFunctions = array("askForInstallUserName",
      "askForInstallUserHomeDir");
    $this->registeredPreUnInstallFunctions = array("askForInstallUserName",
      "askForInstallUserHomeDir");
    $this->initialize();

    $this->autopilotDefiner = "SeleniumServer";
    $this->installCommands = array( "apt-get install selenium" );
    $this->programDataFolder = "/opt/selenium"; // command and app dir name
    $this->uninstallCommands = array( "rm ".$this->programDataFolder );
    $this->programNameMachine = "selenium"; // command and app dir name
    $this->programNameFriendly = "! Selenium !"; // 12 chars
    $this->programNameInstaller = "Selenium";
    $this->initialize();
  }

}