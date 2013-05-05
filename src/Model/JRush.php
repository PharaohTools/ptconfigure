<?php

Namespace Model;

class BoxBoss extends BasePHPApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "BoxBoss";
    $this->fileSources = array(
      array(
        "https://bitbucket.org/phpengine/jrush.git",
        "jrush",
        null // can be null for none
      )
    );
    $this->programNameMachine = "jrush"; // command and app dir name
    $this->programNameFriendly = "JRush CLI !!"; // 12 chars
    $this->programNameInstaller = "JRush - Joomla Command Line";
    $this->programExecutorTargetPath = 'jrush/src/Bootstrap.php';
    $this->initialize();
  }

}