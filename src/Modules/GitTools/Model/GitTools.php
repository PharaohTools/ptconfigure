<?php

Namespace Model;

class GitTools extends BaseLinuxApp {

  public function __construct($params) {
    parent::__construct($params);
    $this->autopilotDefiner = "GitTools";
    $this->installCommands = array("apt-get install -y git git-core gitk git-cola");
    $this->uninstallCommands = array("apt-get remove -y git git-core gitk git-cola");
    $this->programDataFolder = "";
    $this->programNameMachine = "gittools"; // command and app dir name
    $this->programNameFriendly = "!Git Tools!!"; // 12 chars
    $this->programNameInstaller = "Git Tools";
    $this->initialize();
  }

}