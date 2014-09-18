<?php

Namespace Model;

class AptUbuntu extends BasePackager {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $packagerName = "Apt";

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Apt";
        $this->programDataFolder = "";
        $this->programNameMachine = "apt"; // command and app dir name
        $this->programNameFriendly = "!Apt!!"; // 12 chars
        $this->programNameInstaller = "Apt";
        $this->statusCommand = "apt-get" ;
        $this->initialize();
    }

    public function isInstalled($packageName) {
        if (!is_array($packageName)) { $packageName = array($packageName) ; }
        $passing = true ;
        foreach ($packageName as $package) {
            $out = $this->executeAndLoad("sudo apt-cache policy {$package}") ;
            if (strpos($out, "Installed: (none)") != false) { $passing = false ; } }
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
            $logging->log($lmsg) ;
            \BootStrap::setExitCode(1) ;
            return false ; }
        foreach ($packageName as $package) {
            if (!is_null($version)) {
                 $versionToInstall = "" ;
            }
            $out = $this->executeAndOutput("sudo apt-get install $package -y --force-yes");
            if (strpos($out, "Setting up $package") != false) {
                $logging->log("Adding Package $package from the Packager {$this->programNameInstaller} executed correctly") ; }
            else if (strpos($out, "is already the newest version.") != false) {
                $ltext  = "Package $package from the Packager {$this->programNameInstaller} is " ;
                $ltext .= "already installed, so not installing." ;
                $logging->log($ltext) ; }
            else if (strpos($out, "ldconfig deferred processing now taking place") == false) {
                $logging->log("Adding Package $package from the Packager {$this->programNameInstaller} did not execute correctly") ;
                return false ; } }
        return true ;
    }

    public function removePackage($packageName) {
        $packageName = $this->getPackageName($packageName);
        $out = $this->executeAndOutput("sudo apt-get remove $packageName -y --force-yes");
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ( strpos($out, "The following packages will be REMOVED") != false ) {
            $logging->log("Removed Package {$packageName} from the Packager {$this->programNameInstaller}") ;
            return false ; }
        else if ( strpos($out, "is not installed, so not removed") != false) {
            $ltext  = "Package {$packageName} from the Packager {$this->programNameInstaller} is " ;
            $ltext .= "not installed, so not removed." ;
            $logging->log($ltext) ;
            return false ; }
        return true ;
    }

    public function update($autopilot = null) {
        $out = $this->executeAndOutput("sudo apt-get update -y");
        if (strpos($out, "Done") != false) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Updating the Packager {$this->programNameInstaller} did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function versionCompatible($autopilot = null) {
        $out = $this->executeAndOutput("sudo apt-get update -y");
        if (strpos($out, "Done") != false) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Updating the Packager {$this->programNameInstaller} did not execute correctly") ;
            return false ; }
        return true ;
    }

}