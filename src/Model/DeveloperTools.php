<?php

Namespace Model;

class DeveloperTools extends BaseLinuxApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "MysqlTools";
    $this->installCommands = array( "apt-get install -y geany bluefish kompozer emma" );
    $this->uninstallCommands = array( "apt-get remove -y geany bluefish kompozer emma" );
    $this->programDataFolder = "/opt/DeveloperTools"; // command and app dir name
    $this->programNameMachine = "developertools"; // command and app dir name
    $this->programNameFriendly = "Devel Tools!"; // 12 chars
    $this->programNameInstaller = "Developer Tools";
    $this->initialize();
  }

}