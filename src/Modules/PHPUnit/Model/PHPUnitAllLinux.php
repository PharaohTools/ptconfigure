<?php

Namespace Model;

class PHPUnitAllLinux extends BasePHPApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
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
            "https://github.com/phpengine/cleopatra-phpunit-php-timer",
            "php-timer",
            null // can be null for none
          ),
          array(
            "https://github.com/sebastianbergmann/php-text-template.git",
            "php-text-template",
            null // can be null for none
          ),
        );
        $this->programNameMachine = "phpunit"; // command and app dir name
        $this->programNameFriendly = " PHP Unit ! "; // 12 chars
        $this->programNameInstaller = "PHP Unit";
        $this->programExecutorTargetPath = 'phpunit/phpunit.php';
        $this->statusCommand = "phpunit --version" ;
        $this->initialize();
    }

}