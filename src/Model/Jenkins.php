<?php

Namespace Model;

class Jenkins extends BaseLinuxApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "Jenkins";
    $this->installCommands = array( "apt-get install -y jenkins" );
    $this->uninstallCommands = array( "apt-get remove -y jenkins" );
    $this->programDataFolder = "/var/lib/jenkins"; // command and app dir name
    $this->programNameMachine = "jenkins"; // command and app dir name
    $this->programNameFriendly = " ! Jenkins !"; // 12 chars
    $this->programNameInstaller = "Jenkins";
    $this->initialize();
  }

}