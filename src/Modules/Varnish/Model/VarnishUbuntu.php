<?php

Namespace Model;

class VarnishUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "Varnish";
        $this->installCommands = $this->getInstallCommands() ;
        $this->uninstallCommands = array(
            array("command" => array( "sudo apt-get remove -y varnish") )
        ) ;
        $this->programDataFolder = "/var/lib/varnish"; // command and app dir name
        $this->programNameMachine = "varnish"; // command and app dir name
        $this->programNameFriendly = " ! Varnish !"; // 12 chars
        $this->programNameInstaller = "Varnish";
        $this->statusCommand = "command varnish" ;
        $this->versionInstalledCommand = "sudo apt-cache policy varnish" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy varnish" ;
        $this->versionLatestCommand = "sudo apt-cache policy varnish" ;
        $this->initialize();
    }

    // @todo this should definitely be using a package manager module
    protected function getInstallCommands() {
        $ray = array(
            array("command" => array( "sudo apt-get install -y varnish") )
        ) ;
        if (isset($this->params["with-guest-additions"]) && $this->params["with-guest-additions"]==true) {
            array_push($ray, array("command" => array( "sudo apt-get install varnish-guest-additions-iso") ) ) ; }
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