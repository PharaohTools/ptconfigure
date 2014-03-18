<?php

Namespace Model;

class PHPCSAllLinux extends BasePHPApp {

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
        $this->autopilotDefiner = "PHPCS";
        $this->fileSources = array(
          array(
            "https://github.com/phpengine/cleopatra-phpcs.git",
            "phpcs",
            null // can be null for none
          )
        );
        $this->programNameMachine = "phpcs"; // command and app dir name
        $this->programNameFriendly = "PHP CSniffer"; // 12 chars
        $this->programNameInstaller = "PHP Code Sniffer";
        $this->programExecutorTargetPath = 'phpcs/PHP_CodeSniffer-1.5.0RC1/CodeSniffer.php';
        $this->statusCommand = "sudo phpcs --version" ;
        $this->initialize();
    }

}