<?php

Namespace Model;

class VirtualboxWindows extends BaseWindowsApp {

    // Compatibility
    public $os = array("Windows") ;
    public $linuxType = array("None") ;
    public $distros = array("None") ;
    public $versions = array("6") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    // Virtualbox requested version
    public $vbv ;

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
            array("method"=> array("object" => $this, "method" => "askForVirtualboxVersion", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "setPackageUrl", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "executeDependencies", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("WinExe", "Virtualbox")) ),
        ) ;
        if (isset($this->params["with-guest-additions"]) && $this->params["with-guest-additions"]==true) {
            array_push($ray, array("command" => array( "sudo apt-get install -y virtualbox-guest-additions-iso") ) ) ; }
        return $ray ;
    }

    protected function askForVirtualboxVersion(){
        $ao = array("4.3.18") ;
        if (isset($this->params["version"]) && in_array($this->params["version"], $ao)) {
            $this->vbv = $this->params["version"] ; }
        else if (isset($this->params["guess"])) {
            $count = count($ao)-1 ;
            $this->vbv = $ao[$count] ; }
        else {
            $question = 'Enter Virtualbox Version';
            $this->vbv = self::askForArrayOption($question, $ao, true); }
    }

    protected function setPackageUrl(){
        $pus = array(
            "4.3.18" => "http://download.virtualbox.org/virtualbox/4.3.18/VirtualBox-4.3.18-96516-Win.exe" ,
        ) ;
        $this->packageUrl = $pus[$this->params["version"]] ;
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