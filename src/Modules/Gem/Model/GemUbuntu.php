<?php

Namespace Model;

class GemUbuntu extends BasePackager {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $packagerName = "gem";

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Gem";
        $this->programDataFolder = "";
        $this->programNameMachine = "gem"; // command and app dir name
        $this->programNameFriendly = "!Gem!!"; // 12 chars
        $this->programNameInstaller = "Gem";
        $this->initialize();
    }

    public function isInstalled($packageName) {
        $out = $this->executeAndLoad("sudo gem list {$packageName} -i") ;
        return (strpos($out, "true") != false) ? true : false ;
    }

    public function installPackage($packageName, $autopilot = null) {
        $packageName = $this->getPackageName($packageName);
        $returnCode = $this->executeAndOutput("sudo gem install -y $packageName");
        if ($returnCode !== 0) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Adding Package {$packageName} from the Packager {$this->programNameInstaller} did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function removePackage($packageName, $autopilot = null) {
        $packageName = $this->getPackageName($packageName);
        $returnCode = $this->executeAndOutput("sudo gem remove -y $packageName");
        if ($returnCode !== 0) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Removing Package {$packageName} from the Packager {$this->programNameInstaller} did not execute correctly") ;
            return false ; }
        return true ;
    }

}