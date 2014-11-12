<?php

Namespace Model;

class XvfbUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04", "14.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Xvfb";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "vnc4server")) ),
        ) ;
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "vnc4server")) ),
        ) ;
        $this->programDataFolder = "/opt/vnc"; // command and app dir name
        $this->programNameMachine = "vnc"; // command and app dir name
        $this->programNameFriendly = " ! Xvfb !"; // 12 chars
        $this->programNameInstaller = "Xvfb";
        $this->statusCommand = "which vncserver" ;
        $this->versionInstalledCommand = "sudo apt-cache policy vnc4server" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy vnc4server" ;
        $this->versionLatestCommand = "sudo apt-cache policy vnc4server" ;
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