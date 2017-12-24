<?php

Namespace Model;

class VirtualboxWindows extends BaseWindowsApp {

    // Compatibility
    public $os = array("Windows", "WINNT") ;
    public $linuxType = array("None") ;
    public $distros = array("None") ;
    public $versions = array("6", "7", "8", "9", '10') ;
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
        $this->uninstallCommands = array( array("command" => array( SUDOPREFIX."apt-get remove -y virtualbox") ) ) ;
        $this->programDataFolder = "/var/lib/virtualbox"; // command and app dir name
        $this->programNameMachine = "virtualbox"; // command and app dir name
        $this->programNameFriendly = " ! Virtualbox !"; // 12 chars
        $this->programNameInstaller = "Virtualbox";
        $this->statusCommand = "where.exe VBoxManage" ;
        $this->versionInstalledCommand = SUDOPREFIX."apt-cache policy virtualbox" ;
        $this->versionRecommendedCommand = SUDOPREFIX."apt-cache policy virtualbox" ;
        $this->versionLatestCommand = SUDOPREFIX."apt-cache policy virtualbox" ;
        $this->initialize();
    }

    // @todo this should definitely be using a package manager module
    protected function getInstallCommands() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $ray = array(
            array("method"=> array("object" => $this, "method" => "askForVirtualboxVersion", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "setPackageUrl", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "executeDependencies", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("WinExe", "Virtualbox")) ),
        ) ;
        if (isset($this->params["with-guest-additions"]) && $this->params["with-guest-additions"]==true) {
            $logging->log("Virtualbox Guest additions have been requested by parameter, but are installed by default on OSx", $this->getModuleName()) ;
//            array_push($ray, array("command" => array( SUDOPREFIX."apt-get install -y virtualbox-guest-additions-iso") ) ) ;
        }
        return $ray ;
    }

    protected function askForVirtualboxVersion(){
        $ao = array("5.2.0") ;
        if (isset($this->params["version"]) && in_array($this->params["version"], $ao)) {
            $this->vbv = $this->params["version"] ; }
        else if (isset($this->params["guess"])) {
            $index = count($ao)-1 ;
            $this->vbv = $ao[$index] ; }
        else {
            $question = 'Enter Virtualbox Version';
            $this->vbv = self::askForArrayOption($question, $ao, true); }
    }

    protected function setPackageUrl(){
        $pus = array(
            "5.2.0" => "http://download.virtualbox.org/virtualbox/5.2.0/VirtualBox-5.2.0-118431-Win.exe" ,
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