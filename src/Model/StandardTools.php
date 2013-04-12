<?php

Namespace Model;

class StandardTools extends BaseLinuxApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "StandardTools";
    $this->installCommands = array( "apt-get clean", "apt-get update", "apt-get install -y curl vim drush" );
    $this->uninstallCommands = array( "apt-get clean", "apt-get update", "apt-get remove -y curl vim drush" );
    $this->programDataFolder = "/opt/StandardTools"; // command and app dir name
    $this->programNameMachine = "standardtools"; // command and app dir name
    $this->programNameFriendly = "Std. Tools!!"; // 12 chars
    $this->programNameInstaller = "Standard Tools";
    $this->initialize();
  }

}
