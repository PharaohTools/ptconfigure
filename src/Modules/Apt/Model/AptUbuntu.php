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
        $out = $this->executeAndLoad("sudo apt-cache policy {$packageName}") ;
        return (strpos($out, "Installed: (none)") == false) ? true : false ;
    }

    public function installPackage($packageName, $autopilot = null) {
        $packageName = $this->getPackageName($packageName);
        $out = $this->executeAndOutput("sudo apt-get install -y $packageName");
        if (strpos($out, "ldconfig deferred processing now taking place") != false ||
            strpos($out, "is already the newest version.") != false) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Adding Package {$packageName} from the Packager {$this->programNameInstaller} did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function removePackage($packageName, $autopilot = null) {
        $packageName = $this->getPackageName($packageName);
        $out = $this->executeAndOutput("sudo apt-get remove -y $packageName");
        if (strpos($out, "The following packages will be REMOVED") != false ||
            strpos($out, "is not installed, so not removed") != false) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Removing Package {$packageName} from the Packager {$this->programNameInstaller} did not execute correctly") ;
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