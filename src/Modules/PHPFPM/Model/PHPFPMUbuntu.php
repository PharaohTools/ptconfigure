<?php

Namespace Model;

class PHPFPMUbuntu extends BaseLinuxApp {

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
//            array("method"=> array("object" => $this, "method" => "templateFPMConfig", "params" => array()) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", $this->packages ) ) ),
        );
        $this->programDataFolder = "/opt/PHPFPM"; // command and app dir name
        $this->programNameMachine = "PHPFPM"; // command and app dir name
        $this->programNameFriendly = "PHP FPM!"; // 12 chars
        $this->programNameInstaller = "PHP Fast Process Manager";
        $this->prefixLength = 6 ;
        $this->initialize();
    }

    private function setPackages() {

        if (PHP_MAJOR_VERSION > 6) {
            $this->prefixLength = 6 ;
            $this->packages = array("php7.".PHP_MINOR_VERSION."-fpm") ; }
        else {
            $this->prefixLength = 5 ;
            $this->packages = array("php5-fpm") ; }

    }

    public function askStatus() {
        $modsTextCmd = 'php -m';
        $modsText = $this->executeAndLoad($modsTextCmd) ;
        $pax = $this->packages ;
        foreach ($pax as &$pack) { $pack = substr($pack, $this->prefixLength) ; }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $passing = true ;
        foreach ($pax as $modToCheck) {
            if (!strstr($modsText, $modToCheck)) {
                $logging->log("PHP Module {$modToCheck} is not installed for this PHP installation.", $this->getModuleName()) ;
                $passing = false ; } }
        return $passing ;
    }

    public function templateFPMConfig() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $fileFactory = new \Model\File() ;
        $logging->log("Updating PHP FPM Configuration, ensuring our session save path of /tmp is set.", $this->getModuleName()) ;
        $params = $this->params ;
        $params["file"] = "/etc/php-fpm.conf" ;
        $params["search"] = "php_admin_value[session.save_path] = /tmp/ " ;
        $params["after-line"] = "[global]" ;
        $file = $fileFactory->getModel($params) ;
        $res[] = $file->performShouldHaveLine();
        return in_array(false, $res)==false ;
    }

    public function restartFPM() {
        if (PHP_MAJOR_VERSION > 6) {
            $ps = "php7.".PHP_MINOR_VERSION ; }
        else {
            $ps = "php5" ; }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Stopping any running PHP FPM Processes", $this->getModuleName()) ;
        $comm = "service {$ps}-fpm stop" ;
        $res[] = $this->executeAndGetReturnCode($comm, true, true) ;
        $logging->log("Starting PHP FPM Processes", $this->getModuleName()) ;
        $comm = "service ".$ps.'-fpm start' ;
        $res[] = $this->executeAndGetReturnCode($comm, true, true) ;
        return in_array(false, $res)==false ;
    }

}
