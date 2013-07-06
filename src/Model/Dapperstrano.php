<?php

Namespace Model;

class Dapperstrano extends BasePHPApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "Dapperstrano";
    $this->fileSources = array(
      array(
        "https://github.com/phpengine/dapperstrano.git",
        "dapperstrano",
        null // can be null for none
      )
    );
    $this->programNameMachine = "dapperstrano"; // command and app dir name
    $this->programNameFriendly = " Dapperstrano "; // 12 chars
    $this->programNameInstaller = "Dapperstrano";
    $this->programExecutorTargetPath = 'dapperstrano/src/Bootstrap.php';
    $this->initialize();
  }

}