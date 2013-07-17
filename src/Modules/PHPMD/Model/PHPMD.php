<?php

Namespace Model;

class PHPMD extends BasePHPApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "PHPMD";
    $this->fileSources = array(
      array(
        "https://github.com/phpengine/cleopatra-phpmd.git",
        "phpmd",
        null, // custom branch
      ),
    );
    $this->programNameMachine = "phpmd"; // command and app dir name
    $this->programNameFriendly = "PHP Mess Dt."; // 12 chars
    $this->programNameInstaller = "PHP Mess Detector";
    $this->programExecutorTargetPath = 'phpmd/Executioner.php';
    $this->initialize();

  }

}