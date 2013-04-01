<?php

Namespace Model;

class BoxBoss extends BasePHPApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "BoxBoss";
    $this->fileSources = array(
      array(
        "https://github.com/phpengine/boxboss.git",
        "boxboss",
        null // can be null for none
      )
    );
    $this->programNameMachine = "boxboss"; // command and app dir name
    $this->programNameFriendly = " Box Boss !!"; // 12 chars
    $this->programNameInstaller = "BoxBoss - Update to latest version";
    $this->programExecutorTargetPath = 'boxboss/src/Bootstrap.php';
    $this->initialize();
  }

}