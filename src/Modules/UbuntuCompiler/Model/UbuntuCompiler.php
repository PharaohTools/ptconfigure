<?php

Namespace Model;

class UbuntuCompiler extends BaseLinuxApp {

  public function __construct($params) {
    parent::__construct($params);
    $this->autopilotDefiner = "UbuntuCompiler";
    $this->installCommands = array( "apt-get install -y c++ build-essential make" );
    $this->uninstallCommands = array( "apt-get remove -y c++ build-essential make" );
    $this->programDataFolder = "/opt/UbuntuCompiler"; // command and app dir name
    $this->programNameMachine = "nodejs"; // command and app dir name
    $this->programNameFriendly = "Node JS!"; // 12 chars
    $this->programNameInstaller = "Node JS";
    $this->initialize();
  }

}