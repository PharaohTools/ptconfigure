<?php

Namespace Model;

class PHPModules extends BaseLinuxApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "PHPModules";
    $this->installCommands = array( "apt-get install -y php-gd php-imagick" );
    $this->uninstallCommands = array( "apt-get remove -y php-gd php-imagick" );
    $this->programDataFolder = "/opt/PHPModules"; // command and app dir name
    $this->programNameMachine = "phpmodules"; // command and app dir name
    $this->programNameFriendly = "PHP Mods!"; // 12 chars
    $this->programNameInstaller = "PHP Modules";
    $this->initialize();
  }

}