<?php

Namespace Model;

class Devhelper extends BasePHPApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "PHPCS";
    $this->fileSources = array(
      array(
        "https://github.com/phpengine/devhelper.git",
        "devhelper",
        null // can be null for none
      )
    );
    $this->programNameMachine = "devhelper"; // command and app dir name
    $this->programNameFriendly = " Devhelper "; // 12 chars
    $this->programNameInstaller = "Devhelper";
    $this->programExecutorTargetPath = 'devhelper/src/Bootstrap.php';
    $this->initialize();
  }

}