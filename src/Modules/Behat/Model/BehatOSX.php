<?php

Namespace Model;

class BehatUbuntu extends BehatBase {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array('any') ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Behat";
        $this->programNameMachine = "behat"; // command and app dir name
        $this->programNameFriendly = " Behat "; // 12 chars
        $this->programNameInstaller = "Behat";
        $this->programExecutorTargetPath = 'behat/bin/behat';
        $this->statusCommand = "behat -h" ;
        $this->initialize();
    }


    public function setpreinstallCommands() {
        $ray = array( ) ;
        if (PHP_MAJOR_VERSION == 5) {
            $ray[]["command"][] = SUDOPREFIX." apt-get install -y php-mbstring php-curl php-zip php-dom unzip" ;
        } elseif (PHP_MAJOR_VERSION == 7) {
            $ray[]["command"][] = SUDOPREFIX." apt-get install -y php7.0-mbstring php7.0-curl php7.0-zip php-xml unzip" ;
        }
        $this->preinstallCommands = $ray ;
        return $ray ;
    }

}