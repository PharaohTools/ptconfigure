<?php

Namespace Model;

class LigHTTPDServerUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "LigHTTPDServer";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "lighttpd")) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "lighttpd")) ),
        );
        $this->programDataFolder = "/opt/LigHTTPDServer"; // command and app dir name
        $this->programNameMachine = "lighttpdserver"; // command and app dir name
        $this->programNameFriendly = "LigHTTPD Server!"; // 12 chars
        $this->programNameInstaller = "LigHTTPD Server";
        $this->statusCommand = "sudo lighttpd -v" ;
        $this->versionInstalledCommand = "sudo apt-cache policy lighttpd" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy lighttpd" ;
        $this->versionLatestCommand = "sudo apt-cache policy lighttpd" ;
        $this->initialize();
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 23, 15) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 52, 15) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 52, 15) ;
        return $done ;
    }

}