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

    public function updatePHPIni() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Removing any old extension line from PHP Ini", $this->getModuleName()) ;
        $iniFileLocation = '/private/etc/php.ini' ;
        $params1 = $params2 = $this->params ;
        $params1["file"] = $iniFileLocation ;
        $params1["search"] = 'extension=/opt/local/lib/php55/extensions/no-debug-non-zts-20121212/ssh2.so' ;
        $fileFactory = new \Model\File();
        $file1 = $fileFactory->getModel($params1) ;
        $file1->performShouldNotHaveLine() ;
        $logging->log("Adding extension line from PHP Ini.", $this->getModuleName()) ;
        $params2["file"] = $iniFileLocation ;
        $params1["after-line"] = '[PHP]' ;
        $params1["search"] = 'extension=/opt/local/lib/php55/extensions/no-debug-non-zts-20121212/ssh2.so' ;
        $file2 = $fileFactory->getModel($params2) ;
        $file2->performShouldHaveLine() ;
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
                $logging->log("PHP Module {$modToCheck} does not exist.", $this->getModuleName()) ;
                $passing = false ; } }
        return $passing ;
    }

}
