<?php

Namespace Model;

class PHPSSHMac extends PHPSSHUbuntu {

    // Compatibility
    public $os = array("Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("MacPorts", array("php55-ssh2"))) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("PECL", array("ssh2-beta"))) ),
        );
        $this->uninstallCommands = array(
//            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", array("libssh2-1-dev"))) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("PECL", array("ssh2-beta"))) ),
        );
        $this->initialize();
    }

    public function askStatus() {
        $modsTextCmd = SUDOPREFIX.'php -m';
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
