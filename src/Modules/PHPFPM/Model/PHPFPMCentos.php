<?php

Namespace Model;

class PHPFPMCentos extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Redhat") ;
    public $distros = array("any") ;
    public $versions = array( array("5.9", "+")) ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    public $packages = array("php-fpm" ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Yum", $this->packages ) ) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", $this->packages ) ) ),
        );
        $this->programDataFolder = "/opt/PHPFPM"; // command and app dir name
        $this->programNameMachine = "PHPFPM"; // command and app dir name
        $this->programNameFriendly = "PHP FPM!"; // 12 chars
        $this->programNameInstaller = "PHP Fast Process Manager";
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


    public function restartFPM() {

        if (PHP_MAJOR_VERSION > 6) {
            $ps = "php7.0" ; }
        else {
            $ps = "php" ; }

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
