<?php

Namespace Model;

class AptUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $aptName ;
    protected $actionsToMethods =
        array(
            "ins" => "performAptCreate",
            "remove" => "performAptRemove",
            "exists" => "performAptExistenceCheck",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Apt";
        $this->programDataFolder = "";
        $this->programNameMachine = "apt"; // command and app dir name
        $this->programNameFriendly = "!Apt!!"; // 12 chars
        $this->programNameInstaller = "Apt";
        $this->initialize();
    }

    protected function performAptCreate() {
        $this->setApt();
        return $this->create();
    }

    protected function performAptSetPassword() {
        $this->setApt();
        $this->setPassword();
    }

    protected function performAptRemove() {
        $this->setApt();
        $result = $this->remove();
        return $result ;
    }

    public function isInstalled($packageName) {
        $out = $this->executeAndLoad("sudo apt-cache policy {$packageName}") ;
        return (strpos($out, "Installed: (none)") == false) ? true : false ;
    }

    public function installPackage($packageName, $autopilot = null) {
        $packageName = $this->getPackageName($packageName);
        $returnCode = $this->executeAndOutput("sudo apt-get install -y $packageName");
        if ($returnCode !== 0) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Adding Package {$packageName} from the Packager {$this->programNameInstaller} did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function removePackage($packageName, $autopilot = null) {
        $packageName = $this->getPackageName($packageName);
        $returnCode = $this->executeAndGetReturnCode("sudo apt-get remove -y $packageName");
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