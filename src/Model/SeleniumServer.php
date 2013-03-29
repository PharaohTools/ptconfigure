<?php

Namespace Model;

class SeleniumServer extends BaseLinuxApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "SeleniumServer";
    $this->installCommands = array( "apt-get install selenium" );
    $this->uninstallCommands = array( "apt-get remove selenium" );
    $this->programDataFolder = "/opt/selenium"; // command and app dir name
    $this->programNameMachine = "selenium"; // command and app dir name
    $this->programNameFriendly = " !Selenium!!"; // 12 chars
    $this->programNameInstaller = "Selenium";
    $this->initialize();
  }

}