<?php

Namespace Model;

class YumUbuntu extends BasePackager {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $packagerName = "Yum";

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Yum";
        $this->programDataFolder = "";
        $this->programNameMachine = "yum"; // command and app dir name
        $this->programNameFriendly = "!Yum!!"; // 12 chars
        $this->programNameInstaller = "Yum";
        $this->statusCommand = "yum" ;
        $this->initialize();
    }

    public function isInstalled($packageName) {
        if (!is_array($packageName)) { $packageName = array($packageName) ; }
        $passing = true ;
        foreach ($packageName as $package) {
            $out = $this->executeAndLoad("sudo yum-cache policy {$package}") ;
            if (strpos($out, "Installed: (none)") != false) { $passing = false ; } }
        return $passing ;
    }

    public function installPackage($packageName, $autopilot = null) {
        $packageName = $this->getPackageName($packageName);
        if (!is_array($packageName)) { $packageName = array($packageName) ; }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        foreach ($packageName as $package) {
            $out = $this->executeAndOutput("sudo yum-get install $packageName -y --force-yes");
            if (strpos($out, "ldconfig deferred processing now taking place") != false) {
                $logging->log("Adding Package $package from the Packager {$this->programNameInstaller} did not execute correctly") ;
                return false ; }
            else if (strpos($out, "is already the newest version.") != false) {
                $ltext  = "Package $package from the Packager {$this->programNameInstaller} is " ;
                $ltext .= "already installed, so not installing." ;
                $logging->log($ltext) ;
                return false ; } }
        return true ;
    }

    public function removePackage($packageName, $autopilot = null) {
        $packageName = $this->getPackageName($packageName);
        $out = $this->executeAndOutput("sudo yum-get remove -y $packageName");
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
        $out = $this->executeAndOutput("sudo yum-get update -y");
        if (strpos($out, "Done") != false) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Updating the Packager {$this->programNameInstaller} did not execute correctly") ;
            return false ; }
        return true ;
    }

}