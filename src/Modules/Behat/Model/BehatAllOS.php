<?php

Namespace Model;

class BehatAllOS extends BaseComposerApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
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
        $ray[]["command"][] = SUDOPREFIX." apt-get install -y php-mbstring php-curl php-zip unzip" ;
        $this->preinstallCommands = $ray ;
        return $ray ;
    }

}