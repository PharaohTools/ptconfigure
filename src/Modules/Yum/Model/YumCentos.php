<?php

Namespace Model;

class YumCentos extends BasePackager {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Redhat") ;
    public $distros = array("any") ;
    public $versions = array(array("5", "+")) ;
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
            $out = $this->executeAndLoad(SUDOPREFIX."yum list installed | grep {$package}") ;
            $passing = (strlen($out) > 0)  ? true : false ; }
        return $passing ;
    }

    public function installPackage($packageName, $version=null, $versionAccuracy=null) {
        $packageName = $this->getPackageName($packageName);
        if (!is_array($packageName)) { $packageName = array($packageName) ; }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (count($packageName) > 1 && ($version != null || $versionAccuracy != null) ) {
            // @todo multiple versioned packages should work!!
            $lmsg = "Multiple Packages were provided to the Packager {$this->programNameInstaller} at once with versions." ;
            $logging->log($lmsg, $this->getModuleName()) ;
            \Core\BootStrap::setExitCode(1) ;
            return false ; }
        foreach ($packageName as $package) {
            $out = $this->executeAndOutput(SUDOPREFIX."yum install $package -y");
            if (strpos($out, "Complete!") != false ) {
                $logging->log("Added Package $package from the Packager {$this->programNameInstaller} successfully", $this->getModuleName()) ;
                return true ; }
            else if (strpos($out, "Nothing to do") != false) {
                $logging->log("Package $package from the Packager {$this->programNameInstaller} already installed", $this->getModuleName()) ;
                return true; }
            else if (strpos($out, "already installed and latest version.") != false) {
                $ltext  = "Package $package from the Packager {$this->programNameInstaller} is " ;
                $ltext .= "already installed, so not installing." ;
                $logging->log($ltext, $this->getModuleName()) ;
                return true ; }
            else {
                $logging->log("Adding Package $package from the Packager {$this->programNameInstaller} did not execute correctly", $this->getModuleName()) ;
                return false ; } }
        return true ;
    }

    public function removePackage($packageName) {
        $packageName = $this->getPackageName($packageName);
        $out = $this->executeAndOutput(SUDOPREFIX."yum remove -y $packageName");
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ( strpos($out, "Removed:") != false ) {
            $logging->log("Removed Package {$packageName} from the Packager {$this->programNameInstaller}", $this->getModuleName()) ;
            return false ; }
        else if ( strpos($out, "No match for argument: ".$packageName) != false) {
            $ltext  = "Package {$packageName} from the Packager {$this->programNameInstaller} is " ;
            $ltext .= "not installed, so not removed." ;
            $logging->log($ltext, $this->getModuleName()) ;
            return false ; }
        return true ;
    }

    public function update() {
        $out = $this->executeAndOutput(SUDOPREFIX."yum update -y");
        if (strpos($out, "No packages marked for update") != false || strpos($out, "No packages marked for update") != false) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Updating the Packager {$this->programNameInstaller} did not execute correctly", $this->getModuleName()) ;
            return false ; }
        return true ;
    }

}