<?php

Namespace Model;

class RubySystem extends BaseLinuxApp {

  public function __construct($params) {
    parent::__construct($params);
    $this->autopilotDefiner = "RubySystem";
    $this->installCommands = array(
      "apt-get install -y ruby" );
    $this->uninstallCommands = array(
      "apt-get remove -y ruby" );
    $this->programDataFolder = "/opt/rubysystem";
    $this->programNameMachine = "ruby"; // command and app dir name
    $this->programNameFriendly = "Ruby System!"; // 12 chars
    $this->programNameInstaller = "Ruby, System Wide";
    $this->initialize();
  }

}