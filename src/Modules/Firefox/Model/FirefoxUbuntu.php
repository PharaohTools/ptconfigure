<?php

Namespace Model;

class FirefoxUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("12.04", "12.10") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Firefox";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "firefox")) ),
            array("method"=> array("object" => $this, "method" => "apacheRestart", "params" => array())) );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "firefox")) ),
            array("method"=> array("object" => $this, "method" => "apacheRestart", "params" => array())) );
        $this->programDataFolder = "/opt/Firefox"; // command and app dir name
        $this->programNameMachine = "firefox"; // command and app dir name
        $this->programNameFriendly = "Firefox!"; // 12 chars
        $this->programNameInstaller = "Firefox";
        $this->statusCommand = "firefox -v" ;
        $this->versionInstalledCommand = "sudo apt-cache policy firefox" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy firefox" ;
        $this->versionLatestCommand = "sudo apt-cache policy firefox" ;
        $this->initialize();
    }

    public function apacheRestart() {
        $serviceFactory = new Service();
        $serviceManager = $serviceFactory->getModel($this->params) ;
        $serviceManager->setService("apache2");
        $serviceManager->restart();
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 22, 17) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 53, 17) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 53, 17) ;
        return $done ;
    }

}