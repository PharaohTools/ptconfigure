<?php

Namespace Model;

class CitadelUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "Citadel";
        $newRootUser = $this->getNewRootUser() ;
        $newRootPass = $this->getNewRootPass() ;
        $serverIp = $this->getNewServerListenIp() ;
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "debconf-utils")) ),
            array("command"=> array(
                "export DEBIAN_FRONTEND=noninteractive",
                "echo citadel-server citadel/Administrator string $newRootUser | sudo debconf-set-selections",
                "echo citadel-server citadel/Password password $newRootPass | sudo debconf-set-selections",
                "echo citadel-server citadel/Password_again password $newRootPass | sudo debconf-set-selections",
                "echo citadel-server citadel/ServerIPAddress string $serverIp | sudo debconf-set-selections",
                "echo citadel-server citadel/LoginType select Internal | sudo debconf-set-selections",
                // @todo below should have an extra parameter, it throws an error
                //"echo citadel-server citadel/BadUser error | sudo debconf-set-selections",
                "echo citadel-webcit citadel/WebcitApacheIntegration select	Apache2 | sudo debconf-set-selections",
                "echo citadel-webcit citadel/WebcitHttpPort string 8504 | sudo debconf-set-selections",
                "echo citadel-webcit citadel/WebcitHttpsPort string	-1 | sudo debconf-set-selections",
                "echo citadel-webcit citadel/WebcitOfferLang select UNLIMITED | sudo debconf-set-selections",
            ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "citadel-suite")) ),
            array("method"=> array("object" => $this, "method" => "citadelRestart", "params" => array()))
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "citadel-suite")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "citadel-server")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "citadel-client")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "citadel-webcit")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "debconf-utils")) ),
        );
        $this->programDataFolder = "/opt/Citadel"; // command and app dir name
        $this->programNameMachine = "citadel"; // command and app dir name
        $this->programNameFriendly = "Citadel Server!"; // 12 chars
        $this->programNameInstaller = "Citadel Server";
        // @todo this always says installed
        $this->statusCommand = "sudo citadel -v" ;
        $this->versionInstalledCommand = "sudo apt-cache policy citadel-server" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy citadel-server" ;
        $this->versionLatestCommand = "sudo apt-cache policy citadel-server" ;
        $this->initialize();
    }

    protected function getNewRootUser() {
        if (isset($this->params["citadel-root-user"])) {
            $newRootPass = $this->params["citadel-root-user"] ; }
        else if (AppConfig::getProjectVariable("citadel-default-root-user") != "") {
            $newRootPass = AppConfig::getProjectVariable("citadel-default-root-user") ; }
        else {
            $newRootPass = "cleopatra" ; }
        return $newRootPass;
    }

    protected function getNewRootPass() {
        if (isset($this->params["citadel-root-pass"])) {
            $newRootPass = $this->params["citadel-root-pass"] ; }
        else if (AppConfig::getProjectVariable("citadel-default-root-pass") != "") {
            $newRootPass = AppConfig::getProjectVariable("citadel-default-root-pass") ; }
        else {
            $newRootPass = "cleopatra" ; }
        return $newRootPass;
    }

    protected function getNewServerListenIp() {
        if (isset($this->params["citadel-listen-ip"])) {
            $newRootPass = $this->params["citadel-listen-ip"] ; }
        else if (AppConfig::getProjectVariable("citadel-default-listen-ip") != "") {
            $newRootPass = AppConfig::getProjectVariable("citadel-default-listen-ip") ; }
        else {
            $newRootPass = "0.0.0.0" ; }
        return $newRootPass;
    }

    public function citadelRestart() {
        $serviceFactory = new Service();
        $serviceManager = $serviceFactory->getModel($this->params) ;
        $serviceManager->setService("citadel");
        $serviceManager->restart();
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 29, 6) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 49, 6) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 49, 6) ;
        return $done ;
    }

}