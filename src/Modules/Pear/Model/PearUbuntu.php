<?php

Namespace Model;

class PearUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $pearName ;
    protected $actionsToMethods =
        array(
            "install" => "performPearCreate",
            "remove" => "performPearRemove",
            "exists" => "performPearExistenceCheck",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Pear";
        $this->programDataFolder = "";
        $this->programNameMachine = "pear"; // command and app dir name
        $this->programNameFriendly = "!Pear!!"; // 12 chars
        $this->programNameInstaller = "Pear";
        $this->initialize();
    }

    protected function performPearCreate() {
        $this->setPear();
        return $this->create();
    }

    protected function performPearExists() {
        $this->setPear();
        $this->setPassword();
    }

    protected function performPearRemove() {
        $this->setPear();
        $result = $this->remove();
        return $result ;
    }

    public function isInstalled($packageName) {
        $out = $this->executeAndLoad("sudo pear list {$packageName} -i") ;
        return (strpos($out, "true") != false) ? true : false ;
    }

    public function installPackage($packageName, $autopilot = null) {
        $packageName = $this->getPackageName($packageName);
        $returnCode = $this->executeAndOutput("sudo pear install -y $packageName");
        if ($returnCode !== 0) {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Adding Package {$packageName} from the Packager {$this->programNameInstaller} did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function removePackage($packageName, $autopilot = null) {
        $packageName = $this->getPackageName($packageName);
        $returnCode = $this->executeAndGetReturnCode("sudo pear remove -y $packageName");
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