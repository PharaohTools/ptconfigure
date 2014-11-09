<?php

Namespace Model;

class VirtualboxWindows extends BaseWindowsApp {

    // Compatibility
    public $os = array("Windows") ;
    public $linuxType = array() ;
    public $distros = array("Windows") ;
    public $versions = array("6") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    // Windows Package
    public $packageUrl ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Virtualbox";
        $this->installCommands = $this->getInstallCommands() ;
        $this->uninstallCommands = array( array("command" => array( "sudo apt-get remove -y virtualbox") ) ) ;
        $this->programDataFolder = "/var/lib/virtualbox"; // command and app dir name
        $this->programNameMachine = "virtualbox"; // command and app dir name
        $this->programNameFriendly = " ! Virtualbox !"; // 12 chars
        $this->programNameInstaller = "Virtualbox";
        $this->statusCommand = "where.exe VBoxManage" ;
        $this->versionInstalledCommand = "sudo apt-cache policy virtualbox" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy virtualbox" ;
        $this->versionLatestCommand = "sudo apt-cache policy virtualbox" ;
        $this->initialize();
    }

    // @todo this should definitely be using a package manager module
    protected function getInstallCommands() {
        $ray = array(
            array("command" => array( "sudo apt-get install -y virtualbox") )
        ) ;
        if (isset($this->params["with-guest-additions"]) && $this->params["with-guest-additions"]==true) {
            array_push($ray, array("command" => array( "sudo apt-get install -y virtualbox-guest-additions-iso") ) ) ; }
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