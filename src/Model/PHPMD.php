<?php

Namespace Model;

class PHPMD extends BasePHPApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "PHPMD";
    $this->fileSources = array(
      array(
        "https://github.com/phpengine/boxboss-phpmd.git",
        "phpmd",
        null, // custom branch
        true // submodules also ?
      )
    );
    $this->programNameMachine = "phpmd"; // command and app dir name
    $this->programNameFriendly = "PHP Mess Dt."; // 12 chars
    $this->programNameInstaller = "PHP Mess Detector";
    $this->programExecutorTargetPath = 'phpmd/src/bin/phpmd.php';
    $this->initialize();
  }

}