<?php

Namespace Model;

class NginxServerUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "NginxServer";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "nginx")) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "nginx")) ),
        );
        $this->programDataFolder = "/opt/NginxServer"; // command and app dir name
        $this->programNameMachine = "nginxserver"; // command and app dir name
        $this->programNameFriendly = "Nginx Server!"; // 12 chars
        $this->programNameInstaller = "Nginx Server";
        $this->statusCommand = "sudo nginx -v" ;
        $this->versionInstalledCommand = "sudo apt-cache policy nginx" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy nginx" ;
        $this->versionLatestCommand = "sudo apt-cache policy nginx" ;
        $this->initialize();
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 22, 15) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 51, 17) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 51, 17) ;
        return $done ;
    }

}