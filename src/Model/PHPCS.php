<?php

Namespace Model;

class PHPCS extends BasePHPApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "PHPCS";
    $this->fileSources = array(
      array(
        "https://github.com/phpengine/boxboss-phpcs.git",
        "phpcs",
        null // can be null for none
      )
    );
    $this->programNameMachine = "phpcs"; // command and app dir name
    $this->programNameFriendly = "PHP CSniffer"; // 12 chars
    $this->programNameInstaller = "PHP Code Sniffer";
    $this->programExecutorTargetPath = 'phpcs/PHP_CodeSniffer-1.5.0RC1/CodeSniffer.php';
    $this->initialize();
  }

}