<?php

Namespace Model;

class XvfbUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array( array("11.04" => "+")) ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Xvfb";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "xvfb")) ),
        ) ;
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "xvfb")) ),
        ) ;
        $this->programDataFolder = "/opt/xvfb"; // command and app dir name
        $this->programNameMachine = "xvfb"; // command and app dir name
        $this->programNameFriendly = " ! Xvfb !"; // 12 chars
        $this->programNameInstaller = "Xvfb";
        $this->statusCommand = "which xvfb" ;
        $this->versionInstalledCommand = "sudo apt-cache policy xvfb4server" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy xvfb4server" ;
        $this->versionLatestCommand = "sudo apt-cache policy xvfb4server" ;
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