<?php

Namespace Model;

class PHPModulesCentos extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Redhat") ;
    public $distros = array("any") ;
    public $versions = array( array("5.9", "+")) ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    public $packages = array("php-pecl-apc", "gd", "gd-devel", "php-gd", "php-curl", "php-mysql" ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Yum", $this->packages ) ) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", $this->packages ) ) ),
        );
        $this->programDataFolder = "/opt/PHPModules"; // command and app dir name
        $this->programNameMachine = "phpmodules"; // command and app dir name
        $this->programNameFriendly = "PHP Mods!"; // 12 chars
        $this->programNameInstaller = "PHP Modules";
        $this->initialize();
    }

    public function askStatus() {
        $modsTextCmd = 'php -m';
        $modsText = $this->executeAndLoad($modsTextCmd) ;
        $pax = $this->packages ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $passing = true ;
        foreach ($pax as $modToCheck) {
            if (!strstr($modsText, $modToCheck)) {
                $logging->log("PHP Module {$modToCheck} is not installed for this PHP installation.") ;
                $passing = false ; } }
        return $passing ;
    }

}
