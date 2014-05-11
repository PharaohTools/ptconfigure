<?php

Namespace Model;

class NagiosServerUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("any") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "NagiosServer";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "nagios3")) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "nagios3")) ),
        );
        $this->programDataFolder = "/opt/NagiosServer"; // command and app dir name
        $this->programNameMachine = "nagiosserver"; // command and app dir name
        $this->programNameFriendly = "Nagios Server!"; // 12 chars
        $this->programNameInstaller = "Nagios Server";
        $this->statusCommand = "sudo nagios3 --version" ;
        $this->versionInstalledCommand = "sudo apt-cache policy nagios3" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy nagios3" ;
        $this->versionLatestCommand = "sudo apt-cache policy nagios3" ;
        $this->initialize();
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 22, 15) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 42, 14) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 42, 14) ;
        return $done ;
    }

}