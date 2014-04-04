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

    public function installPackage($packageName) {
        $packageName = $this->getPackageName($packageName);
        if (!is_array($packageName)) { $packageName = array($packageName) ; }
        $consoleFactory = new \Model\Console();
        $console = $consoleFactory->getModel($this->params);
        foreach ($packageName as $package) {
            $out = $this->executeAndOutput("sudo apt-get install $package -y --force-yes");
            if (strpos($out, "ldconfig deferred processing now taking place") != false) {
                $console->log("Adding Package $package from the Packager {$this->programNameInstaller} executed correctly") ; }
            else if (strpos($out, "is already the newest version.") != false) {
                $ltext  = "Package $package from the Packager {$this->programNameInstaller} is " ;
                $ltext .= "already installed, so not installing." ;
                $console->log($ltext) ; }
            else if (strpos($out, "ldconfig deferred processing now taking place") == false) {
                $console->log("Adding Package $package from the Packager {$this->programNameInstaller} did not execute correctly") ;
                return false ; } }
        return true ;
    }

    public function removePackage($packageName) {
        $packageName = $this->getPackageName($packageName);
        $out = $this->executeAndOutput("sudo apt-get remove $packageName -y --force-yes");
        $consoleFactory = new \Model\Console();
        $console = $consoleFactory->getModel($this->params);
        if ( strpos($out, "The following packages will be REMOVED") != false ) {
            $console->log("Removed Package {$packageName} from the Packager {$this->programNameInstaller}") ;
            return false ; }
        else if ( strpos($out, "is not installed, so not removed") != false) {
            $ltext  = "Package {$packageName} from the Packager {$this->programNameInstaller} is " ;
            $ltext .= "not installed, so not removed." ;
            $console->log($ltext) ;
            return false ; }
        return true ;
    }

    public function update($autopilot = null) {
        $out = $this->executeAndOutput("sudo apt-get update -y");
        if (strpos($out, "Done") != false) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Updating the Packager {$this->programNameInstaller} did not execute correctly") ;
            return false ; }
        return true ;
    }

}