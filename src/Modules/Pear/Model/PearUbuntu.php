<?php

Namespace Model;

class PearUbuntu extends BasePackager {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $packagerName = "pear";

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Pear";
        $this->programDataFolder = "";
        $this->programNameMachine = "pear"; // command and app dir name
        $this->programNameFriendly = "!Pear!!"; // 12 chars
        $this->programNameInstaller = "Pear";
        $this->initialize();
    }

    public function isInstalled($packageName) {
        $out = $this->executeAndLoad("sudo pear list {$packageName} -i") ;
        return (strpos($out, "true") != false) ? true : false ;
    }

    public function installPackage($packageName, $autopilot = null) {
        $packageName = $this->getPackageName($packageName);
        $out = $this->executeAndOutput("sudo pear install -f $packageName");
        if (!is_int(strpos($out, "install ok"))) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Adding Package {$packageName} from the Packager {$this->programNameInstaller} did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function removePackage($packageName, $autopilot = null) {
        $packageName = $this->getPackageName($packageName);
        $out = $this->executeAndOutput("sudo pear uninstall $packageName");
        if (!is_int(strpos($out, "uninstall ok"))) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Removing Package {$packageName} from the Packager {$this->programNameInstaller} did not execute correctly") ;
            return false ; }
        return true ;
    }

}