<?php

Namespace Model;

class GIMPUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04", "13.10", "14.04", "14.10") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "GIMP";
        $this->installCommands = array( "apt-get install -y gimp" );
        $this->uninstallCommands = array( "apt-get remove -y gimp" );
        $this->programDataFolder = "/var/lib/gimp"; // command and app dir name
        $this->programNameMachine = "gimp"; // command and app dir name
        $this->programNameFriendly = " ! GIMP !"; // 12 chars
        $this->programNameInstaller = "GIMP";
        $this->statusCommand = "sudo gimp -v" ;
        $this->versionInstalledCommand = "sudo apt-cache policy gimp" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy gimp" ;
        $this->versionLatestCommand = "sudo apt-cache policy gimp" ;
        $this->initialize();
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 23, 15) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 42, 23) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 42, 23) ;
        return $done ;
    }

}