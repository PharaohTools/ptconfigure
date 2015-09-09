<?php

Namespace Model;

class PHPModulesUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array(array("11.04", "+")) ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    public $packages = array("php-apc", "php5-dev", "php5-gd", "php5-imagick", "php5-curl", "php5-mysql", "php5-memcache", "php5-memcached", "php5-mongo" ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", $this->packages ) ) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", $this->packages ) ) ),
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
        foreach ($pax as &$pack) { $pack = substr($pack, 5) ; }
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
