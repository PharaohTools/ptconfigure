<?php

Namespace Model;

class JRush extends BasePHPApp {

  public function __construct($params) {
    parent::__construct($params);
    $this->autopilotDefiner = "JRush";
    $this->fileSources = array(
      array(
        "https://github.com/phpengine/jrush.git",
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