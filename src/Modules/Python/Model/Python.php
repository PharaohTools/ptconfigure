<?php

Namespace Model;

class Python extends BaseLinuxApp {

  public function __construct($params) {
    parent::__construct($params);
    $this->autopilotDefiner = "Python";
    $this->installCommands = array("apt-get install -y python python-docutils");
    $this->uninstallCommands = array("apt-get remove -y python python-docutils");
    $this->programDataFolder = "";
    $this->programNameMachine = "python"; // command and app dir name
    $this->programNameFriendly = "!Python!!"; // 12 chars
    $this->programNameInstaller = "Python";
    $this->initialize();
  }

}