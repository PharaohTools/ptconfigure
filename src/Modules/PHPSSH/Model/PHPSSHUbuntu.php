<?php

Namespace Model;

class PHPSSHUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "PHPSSH";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", array("libssh2-php"))) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", array("libssh2-php"))) ),
        );
        $this->programDataFolder = "/opt/PHPSSH"; // command and app dir name
        $this->programNameMachine = "phpssh"; // command and app dir name
        $this->programNameFriendly = "PHP SSH!"; // 12 chars
        $this->programNameInstaller = "PHP SSH";
        $this->initialize();
    }

    public function askStatus() {
        $modsTextCmd = 'sudo php -m';
        $modsText = $this->executeAndLoad($modsTextCmd) ;
        $modsToCheck = array("ssh2") ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $passing = true ;
        foreach ($modsToCheck as $modToCheck) {
            if (!strstr($modsText, $modToCheck)) {
                $logging->log("PHP Module {$modToCheck} does not exist.") ;
                $passing = false ; } }
        return $passing ;
    }

}
