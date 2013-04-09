<?php

Namespace Model;

class PHPModules extends BaseLinuxApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "PHPModules";
    $this->installCommands = array( "apt-get install -y php5-gd php5-imagick php5-curl php5-mysql" );
    $this->uninstallCommands = array( "apt-get remove -y php5-gd php5-imagick php5-curl php5-mysql" );
    $this->programDataFolder = "/opt/PHPModules"; // command and app dir name
    $this->programNameMachine = "phpmodules"; // command and app dir name
    $this->programNameFriendly = "PHP Mods!"; // 12 chars
    $this->programNameInstaller = "PHP Modules";
    $this->initialize();
  }

}
