<?php

Namespace Model;

class ApacheModules extends BaseLinuxApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "ApacheModules";
    $this->installCommands = array( "a2enmod rewrite", "a2enmod proxy,",
      "a2enmod deflate", "service apache2 restart", );
    $this->uninstallCommands = array( "apt-get remove -y mysql-workbench" );
    $this->programDataFolder = "/opt/ApacheModules"; // command and app dir name
    $this->programNameMachine = "apachemodules"; // command and app dir name
    $this->programNameFriendly = "Apache Mods!"; // 12 chars
    $this->programNameInstaller = "Apache Modules";
    $this->initialize();
  }

}