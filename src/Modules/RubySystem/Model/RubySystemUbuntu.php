<?php

Namespace Model;

class RubySystemUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "RubySystem";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array( "Apt", "ruby1.9.1" ) ) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array( "Apt", "ruby1.9.1" ) ) ),
        );
        $this->programDataFolder = "/opt/rubysystem";
        $this->programNameMachine = "ruby"; // command and app dir name
        $this->programNameFriendly = "Ruby System!"; // 12 chars
        $this->programNameInstaller = "Ruby, System Wide";
        $this->statusCommand = "ruby -v" ;
        $this->versionInstalledCommand = "sudo apt-cache policy ruby1.9.1" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy ruby1.9.1" ;
        $this->versionLatestCommand = "sudo apt-cache policy ruby1.9.1" ;
        $this->initialize();
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 24, 18) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 56, 18) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 56, 18) ;
        return $done ;
    }

}