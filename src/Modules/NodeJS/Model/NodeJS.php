<?php

Namespace Model;

class NodeJS extends BaseLinuxApp {

  public function __construct($params) {
    parent::__construct($params);
    $this->autopilotDefiner = "NodeJS";
    $this->installCommands = array( "apt-get install -y node nodejs" );
    $this->uninstallCommands = array( "apt-get remove -y node nodejs" );
    $this->programDataFolder = "/opt/NodeJS"; // command and app dir name
    $this->programNameMachine = "nodejs"; // command and app dir name
    $this->programNameFriendly = "Node JS!"; // 12 chars
    $this->programNameInstaller = "Node JS";
    $this->initialize();
  }

}