<?php

Namespace Model;

class PHPMD extends BasePHPApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "PHPMD";
    $this->fileSources = array(
      array(
        "https://github.com/phpmd/phpmd.git",
        "phpmd",
        null, // custom branch
        true // submodules also ?
      ),
      array(
        "https://github.com/manuelpichler/pdepend.git",
        "pdepend",
        null, // custom branch
      )
    );
    $this->programNameMachine = "phpmd"; // command and app dir name
    $this->programNameFriendly = "PHP Mess Dt."; // 12 chars
    $this->programNameInstaller = "PHP Mess Detector";
    $this->programExecutorTargetPath = 'phpmd/src/bin/phpmd.php';
    $this->initialize();
    $this->extraCommandsArray = array(
      'ant ****PROGDIR****/phpmd/src/bin/initialize'
    ); // must be after initialize
    $this->extraPathsArray = array(
      'src/main/php'
    ); // must be after initialize

  }

}