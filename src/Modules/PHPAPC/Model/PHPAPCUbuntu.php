<?php

Namespace Model;

class PHPAPCUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "PHPAPC";
        $this->installCommands = array( "apt-get install -y php-apc" );
        $this->uninstallCommands = array( "apt-get remove -y php-apc" );
        $this->programDataFolder = "/opt/PHPAPC"; // command and app dir name
        $this->programNameMachine = "phpapc"; // command and app dir name
        $this->programNameFriendly = "PHP APC!"; // 12 chars
        $this->programNameInstaller = "PHP APC";
        $this->initialize();
    }

    public function askStatus() {
        $modsTextCmd = 'sudo php -m';
        $modsText = $this->executeAndLoad($modsTextCmd) ;

        $modsToCheck = array("apc") ;
        $consoleFactory = new \Model\Console();
        $console = $consoleFactory->getModel($this->params);
        $passing = true ;

        foreach ($modsToCheck as $modToCheck) {
            if (!strstr($modsText, $modToCheck)) {
                $console->log("PHP Module {$modToCheck} does not exist.") ;
                $passing = false ; } }

        return $passing ;

    }

}
