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
    public $packages ;

    public function __construct($params) {
        $this->setPackages() ;
        parent::__construct($params);
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", $this->packages ) ) ),
        );

        if (PHP_MAJOR_VERSION > 6) {
            $ray = $this->installCommands ;
            $first = $ray[0] ;
            $second =
                array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("PECL", array("mongodb") ) ) ) ;
            $this->installCommands = array($first, $second) ;
        }

        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", $this->packages ) ) ),
        );
        $this->programDataFolder = "/opt/PHPModules"; // command and app dir name
        $this->programNameMachine = "phpmodules"; // command and app dir name
        $this->programNameFriendly = "PHP Mods!"; // 12 chars
        $this->programNameInstaller = "PHP Modules";
        $this->initialize();
    }

    private function setPackages() {
        if (PHP_MAJOR_VERSION > 6) {
            $ps2 = "php7.".PHP_MINOR_VERSION  ;
            $ps1 = "php7.".PHP_MINOR_VERSION."-opcache " ; }
        else {
            $ps2 = "php5" ;
            $ps1 = "php-apc {$ps2}-memcached php5-memcache {$ps2}-mongo {$ps2}-imagick" ; }

        $pstr = "{$ps1} {$ps2}-dev {$ps2}-gd {$ps2}-curl {$ps2}-mysql ".
        "{$ps2}-sqlite {$ps2}-ldap php-pear" ;

        if (PHP_MAJOR_VERSION > 6) {
            $pstr .= " {$ps2}-xml openssl pkg-config" ; }  // openssl, pkg-config are required for mongo in php7

        $this->packages = array( $pstr ) ;
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
