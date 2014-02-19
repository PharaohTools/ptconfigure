<?php

Namespace Model;

class GemUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $gemName ;
    protected $actionsToMethods =
        array(
            "install" => "performGemCreate",
            "remove" => "performGemRemove",
            "exists" => "performGemExistenceCheck",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Gem";
        $this->programDataFolder = "";
        $this->programNameMachine = "gem"; // command and app dir name
        $this->programNameFriendly = "!Gem!!"; // 12 chars
        $this->programNameInstaller = "Gem";
        $this->initialize();
    }

    protected function performGemCreate() {
        $this->setGem();
        return $this->create();
    }

    protected function performGemExists() {
        $this->setGem();
        $this->setPassword();
    }

    protected function performGemRemove() {
        $this->setGem();
        $result = $this->remove();
        return $result ;
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
        $returnCode = $this->executeAndGetReturnCode("sudo gem-get remove -y $packageName");
        if ($returnCode !== 0) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Removing Package {$packageName} from the Packager {$this->programNameInstaller} did not execute correctly") ;
            return false ; }
        return true ;
    }

    private function getPackageName($packageName = null) {
        if (isset($packageName)) {  }
        else if (isset($this->params["package-name"])) {
            $packageName = $this->params["package-name"]; }
        else if (isset($this->params["package-name"])) {
            $packageName = $this->params["package-name"]; }
        else if (isset($autopilot["package-name"])) {
            $packageName = $autopilot["package-name"]; }
        else {
            $packageName = self::askForInput("Enter Package Name:", true); }
        return $packageName ;
    }

}