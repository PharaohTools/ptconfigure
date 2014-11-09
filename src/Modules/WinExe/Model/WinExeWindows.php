<?php

Namespace Model;

class WinExeWindows extends BasePackager {

    // Compatibility
    public $os = array("Windows", "WINNT") ;
    public $linuxType = array() ;
    public $distros = array();
    public $versions = [ ["11.04" => "+" ] ] ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $packagerName = "WinExe";

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "WinExe";
        $this->programDataFolder = "";
        $this->programNameMachine = "winexe"; // command and app dir name
        $this->programNameFriendly = "!WinExe!!"; // 12 chars
        $this->programNameInstaller = "WinExe";
        $this->statusCommand = "winexe-get" ;
        $this->initialize();
    }

    public function isInstalled($packageName) {
        if (!is_array($packageName)) { $packageName = array($packageName) ; }
        $passing = true ;
        foreach ($packageName as $package) {
            $out = $this->executeAndLoad("where /R {$progDir} *{$package}*") ;
            if (strpos($out, $package) !== false) { $passing = false ; } }
        return $passing ;
    }

    public function installPackage($packageName, $version=null, $versionAccuracy=null, $requestingModel=null) {
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
            unlink($this->tempDir."temp.exe") ;
            file_get_contents($requestingModel->packageUrl, $this->tempDir."temp.exe") ;
            $out = $this->executeAndOutput($this->tempDir."temp.exe");
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
        $out = $this->executeAndOutput("sudo winexe-get remove $packageName -y --force-yes");
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

    public function update() {
        $out = $this->executeAndOutput("sudo winexe-get update -y");
        if (strpos($out, "Done") != false) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Updating the Packager {$this->programNameInstaller} did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function versionCompatible() {
        $out = $this->executeAndOutput("sudo winexe-get update -y");
        if (strpos($out, "Done") != false) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Updating the Packager {$this->programNameInstaller} did not execute correctly") ;
            return false ; }
        return true ;
    }

}