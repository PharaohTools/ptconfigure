<?php

Namespace Model;

class MacPortsMac extends BasePackager {

    // Compatibility
    public $os = array("Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("10.4", "10.5", "10.6", "10.7", "10.8", "10.9", "10.10") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $packagerName = "MacPorts";

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "MacPorts";
        $this->programDataFolder = "";
        $this->programNameMachine = "macPorts"; // command and app dir name
        $this->programNameFriendly = "!MacPorts!!"; // 12 chars
        $this->programNameInstaller = "MacPorts";
        $this->statusCommand = "port -v" ;
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "installMacPorts", "params" => array()) ),
        );
        $this->uninstallCommands = array(
        );
        $this->initialize();
    }

    public function installMacPorts() {
        $system = new \Model\SystemDetectionAllOS() ;
        $version = $system->version ;
        switch ($version) {
            case "10.4" :
                $filename = "MacPorts-2.3.3-10.4-Tiger.dmg" ;
                break ;
            case "10.5" :
                $filename = "MacPorts-2.3.3-10.5-Leopard.dmg" ;
                break ;
            case "10.6" :
                $filename = "MacPorts-2.3.3-10.6-SnowLeopard.pkg" ;
                break ;
            case "10.7" :
                $filename = "MacPorts-2.3.3-10.7-Lion.pkg" ;
                break ;
            case "10.8" :
                $filename = "MacPorts-2.3.3-10.8-MountainLion.pkg" ;
                break ;
            case "10.9" :
                $filename = "MacPorts-2.3.3-10.9-Mavericks.pkg" ;
                break ;
            case "10.10" :
                $filename = "MacPorts-2.3.3-10.10-Yosemite.pkg" ;
                break ;
            default :
                $filename = "MacPorts-2.3.3-10.10-Yosemite.pkg" ;
                break ; }
        $url = 'https://distfiles.macports.org/MacPorts/' ;
        $curlCommand = "curl {$url}{$filename} -o /tmp/{$filename}" ;
        $this->executeAndOutput($curlCommand) ;
        if (strpos($filename, ".pkg")) {
            $comm = SUDOPREFIX."installer -pkg /tmp/{$filename} -target /" ;
            $this->executeAndOutput($comm) ; }
        else if (strpos($filename, ".dmg")) {
            $comm = SUDOPREFIX."hdiutil attach /tmp/{$filename}" ;
            $this->executeAndOutput($comm) ;
            $comm = SUDOPREFIX.'installer -pkg /Volumes/MacPorts-2.3.3/MacPorts-2.3.3.pkg -target /' ;
            $this->executeAndOutput($comm) ;
            $comm = SUDOPREFIX."hdiutil detach /Volumes/MacPorts-2.3.3/MacPorts-2.3.3.pkg" ;
            $this->executeAndOutput($comm) ; }
        else {
            // this is a file error
            // @todo logging an error
            return false ; }
        return true ;
    }

    public function isInstalled($packageName) {
        if (!is_array($packageName)) { $packageName = array($packageName) ; }
        $passing = true ;
        foreach ($packageName as $package) {
            $out = $this->executeAndLoad(SUDOPREFIX."port list installed") ;
            if (strpos($out, $package) == false) { $passing = false ; } }
        return $passing ;
    }

    public function installPackage($packageName, $version=null, $versionAccuracy=null) {
        $packageName = $this->getPackageName($packageName);
        if (!is_array($packageName)) { $packageName = array($packageName) ; }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (count($packageName) > 1 && ($version != null || $versionAccuracy != null) ) {
            // @todo multiple versioned packages should work!!
            $lmsg = "Multiple Packages were provided to the Packager {$this->programNameInstaller} at once with versions." ;
            $logging->log($lmsg, $this->getModuleName()) ; ;
            \BootStrap::setExitCode(1) ;
            return false ; }
        foreach ($packageName as $package) {
            if (!is_null($version)) {
                 $versionToInstall = "" ;
            }
            $out = $this->executeAndOutput(SUDOPREFIX."port install $package -y");
            if (strpos($out, "Setting up $package") != false) {
                $logging->log("Adding Package $package from the Packager {$this->programNameInstaller} executed correctly", $this->getModuleName()) ; }
            else if (strpos($out, "is already the newest version.") != false) {
                $ltext  = "Package $package from the Packager {$this->programNameInstaller} is " ;
                $ltext .= "already installed, so not installing." ;
                $logging->log($ltext, $this->getModuleName()) ; }
            else if (strpos($out, "ldconfig deferred processing now taking place") == false) {
                $logging->log("Adding Package $package from the Packager {$this->programNameInstaller} did not execute correctly", $this->getModuleName()) ;
                return false ; } }
        return true ;
    }

    public function removePackage($packageName) {
        $packageName = $this->getPackageName($packageName);
        $out = $this->executeAndOutput(SUDOPREFIX."port uninstall $packageName -y");
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ( strpos($out, "The following packages will be REMOVED") != false ) {
            $logging->log("Removed Package {$packageName} from the Packager {$this->programNameInstaller}", $this->getModuleName()) ;
            return false ; }
        else if ( strpos($out, "is not installed, so not removed") != false) {
            $ltext  = "Package {$packageName} from the Packager {$this->programNameInstaller} is " ;
            $ltext .= "not installed, so not removed." ;
            $logging->log($ltext, $this->getModuleName()) ;
            return false ; }
        return true ;
    }

    public function update() {
        $out = $this->executeAndOutput(SUDOPREFIX."port -v selfupdate");
        if (strpos($out, "The ports tree has been updated.") == false) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Updating the Packager {$this->programNameInstaller} did not execute correctly", $this->getModuleName()) ;
            return false ; }
        return true ;
    }

    public function versionCompatible() {
//        $out = $this->executeAndOutput(SUDOPREFIX."macPorts-get update -y");
//        if (strpos($out, "Done") != false) {
//            $loggingFactory = new \Model\Logging();
//            $logging = $loggingFactory->getModel($this->params);
//            $logging->log("Updating the Packager {$this->programNameInstaller} did not execute correctly", $this->getModuleName()) ;
//            return false ; }
//        return true ;
    }

}