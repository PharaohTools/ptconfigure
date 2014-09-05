<?php

Namespace Model;

class VNCUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "VNC";
        $this->installCommands = $this->getInstallCommands() ;
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "vncserver")) ),
        ) ;
        $this->programDataFolder = "/opt/vnc"; // command and app dir name
        $this->programNameMachine = "vnc"; // command and app dir name
        $this->programNameFriendly = " ! VNC !"; // 12 chars
        $this->programNameInstaller = "VNC";
        $this->statusCommand = "command vboxmanage" ;
        $this->versionInstalledCommand = "sudo apt-cache policy vncserver" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy vncserver" ;
        $this->versionLatestCommand = "sudo apt-cache policy vncserver" ;
        $this->initialize();
    }

    protected function getInstallCommands() {
        $ray = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "vncserver")) ),
        ) ;
        return $ray ;
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