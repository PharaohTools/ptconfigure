<?php

Namespace Model;

class StateDetection extends BaseLinuxApp {

  public function __construct($params) {
    parent::__construct($params);
    $this->autopilotDefiner = "StateDetection";
    $this->installCommands = array("apt-get install -y apache2");
    $this->uninstallCommands = array("apt-get remove -y apache2");
    $this->programDataFolder = "/opt/StateDetection"; // command and app dir name
    $this->programNameMachine = "apacheinstall"; // command and app dir name
    $this->programNameFriendly = "Apache Install!"; // 12 chars
    $this->programNameInstaller = "Apache Install";
    $this->initialize();
  }

}