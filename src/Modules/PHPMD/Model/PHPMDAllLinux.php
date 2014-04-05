<?php

Namespace Model;

class PHPMDAllLinux extends BasePHPApp {

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
        $this->statusCommand = "phpmd --version" ;
        $this->initialize();
    }

}