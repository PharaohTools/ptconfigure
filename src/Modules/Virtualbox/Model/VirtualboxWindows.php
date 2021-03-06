<?php

Namespace Model;

class VirtualboxWindows extends BaseWindowsApp {

    // Compatibility
    public $os = array("Windows", "WINNT") ;
    public $linuxType = array('any') ;
    public $distros = array('any') ;
    public $versions = array('any') ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    // Virtualbox requested version
    public $exeInstallFlags = ' --silent -msiparams ALLUSERS=1' ;
    public $versionsAvailable = ['5.2.0', '6.0.1'] ;
//    public $versionsAvailable = ['6.0.1', '5.2.0'] ;

    // Windows Package
    public $packageUrl ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Virtualbox";
        $this->installCommands = $this->getInstallCommands() ;
        $this->uninstallCommands = array( ) ;
        $pf = (isset($_ENV['ProgramFiles'])) ? $_ENV['ProgramFiles'] : 'C:\\' ;
        $this->programDataFolder = $pf."\\Virtualbox\\"; // command and app dir name
        $this->programNameMachine = "virtualbox"; // command and app dir name
        $this->programNameFriendly = "! VirtualBox !"; // 12 chars
        $this->programNameInstaller = "Virtualbox";
        $this->packageSearchString = "Oracle VM VirtualBox";
        $this->statusCommand = "where.exe VBoxManage" ;
        $this->initialize();
    }

    // @todo this should definitely be using a package manager module
    protected function getInstallCommands() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $ray = array(
            array("method"=> array("object" => $this, "method" => "askForVersion", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "setPackageUrl", "params" => array()) ),
            // array("method"=> array("object" => $this, "method" => "packageDownload", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("WinExe", "Virtualbox")) ),
        ) ;
        if (isset($this->params["with-guest-additions"]) && $this->params["with-guest-additions"]==true) {
            $logging->log("Virtualbox Guest additions have been requested by parameter, but are installed by default on Windows", $this->getModuleName()) ;
//            array_push($ray, array("command" => array( SUDOPREFIX."apt-get install -y virtualbox-guest-additions-iso") ) ) ;
        }
        return $ray ;
    }

    public function setPackageUrl(){
//        var_dump($this->params);
        $pus = array(
            "6.0.1" => "http://download.virtualbox.org/virtualbox/6.0.12/VirtualBox-6.0.12-133076-Win.exe",
            "5.2.0" => "http://download.virtualbox.org/virtualbox/5.2.0/VirtualBox-5.2.0-118431-Win.exe"
        ) ;
        $this->packageUrl = $pus[$this->params["version"]] ;
    }

    public function msiCertificate($text) {
//        'certutil -addstore "TrustedPublisher" oracle-vbox.cer' ;
        $done = substr($text, 23, 15) ;
        return $done ;
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