<?php

Namespace Model;

class BehatAllLinux extends BasePHPApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian", "Redhat") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Installer") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Behat";
        $this->fileSources = array(
          array(
            "https://github.com/Behat/Behat",
            "behat",
            null // can be null for none
          )
        );
        $this->extraCommandsArray = array(
            "cd ****PROGDIR****/behat",
            "curl -s http://getcomposer.org/installer | php" ,
            "php composer.phar install" );
        $this->programNameMachine = "behat"; // command and app dir name
        $this->programNameFriendly = " Behat "; // 12 chars
        $this->programNameInstaller = "Behat";
        $this->programExecutorTargetPath = 'behat/bin/behat';
        $this->initialize();
    }

}