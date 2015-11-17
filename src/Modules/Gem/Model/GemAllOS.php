<?php

Namespace Model;

class GemUbuntu extends BasePackager {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
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
        $out = $this->executeAndLoad(SUDOPREFIX."gem list {$packageName} -i") ;
        return (strpos($out, "true") != false) ? true : false ;
    }

    public function installPackage($packageName, $autopilot = null) {
        $packageName = $this->getPackageName($packageName);
        $returnCode = $this->executeAndOutput(SUDOPREFIX."gem install -y $packageName");
        if ($returnCode !== 0) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Adding Package {$packageName} from the Packager {$this->programNameInstaller} did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function removePackage($packageName, $autopilot = null) {
        $packageName = $this->getPackageName($packageName);
        $returnCode = $this->executeAndOutput(SUDOPREFIX."gem remove -y $packageName");
        if ($returnCode !== 0) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Removing Package {$packageName} from the Packager {$this->programNameInstaller} did not execute correctly") ;
            return false ; }
        return true ;
    }

}