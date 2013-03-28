<?php

Namespace Model;

class PHPUnit extends BasePHPApp {

  public function __construct() {
    parent::__construct();
    $this->autopilotDefiner = "PHPUnit35";
    $this->fileSources = array(
      array(
        "https://github.com/sebastianbergmann/phpunit.git",
        "phpunit",
        "3.5" // can be null for none
      ),
      array(
        "https://github.com/sebastianbergmann/dbunit.git",
        "dbunit",
        "1.0" // can be null for none
      ),
      array(
        "https://github.com/sebastianbergmann/php-file-iterator.git",
        "php-file-iterator",
        "1.2" // can be null for none
      ),
      array(
        "https://github.com/sebastianbergmann/php-code-coverage.git",
        "php-code-coverage",
        "1.0" // can be null for none
      ),
      array(
        "https://github.com/sebastianbergmann/php-token-stream.git",
        "php-token-stream",
        "1.0" // can be null for none
      ),
      array(
        "https://github.com/sebastianbergmann/phpunit-mock-objects.git",
        "phpunit-mock-objects",
        "1.0" // can be null for none
      ),
      array(
        "https://github.com/sebastianbergmann/phpunit-selenium.git",
        "phpunit-selenium",
        "1.0" // can be null for none
      ),
      array(
        "https://github.com/sebastianbergmann/php-timer.git",
        "php-timer",
        null // can be null for none
      ),
    );
    $this->programNameMachine = "phpunit"; // command and app dir name
    $this->programNameFriendly = " PHP Unit ! "; // 12 chars
    $this->programNameInstaller = "PHP Unit";
    $this->programExecutorTargetPath = 'phpunit/phpunit.php';
    $this->initialize();
  }

}