<?php

Namespace Model;

class PHPUnit extends BasePHPApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "PHPUnit35";
    $this->fileSource = "http://github.com/phpengine/boxboss-source-phpunit";
    $this->programNameMachine = "phpunit"; // command to be used on command line
    $this->programNameFriendly = " PHP Unit ! "; // 12 chars
  }

}