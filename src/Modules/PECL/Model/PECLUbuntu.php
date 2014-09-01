<?php

Namespace Model;

class PECLUbuntu extends BasePackager {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $packagerName = "PECL";

    public $actionsToMethods =
        array(
            "pkg-install" => "performInstall",
            "pkg-ensure" => "performInstall",
            "pkg-remove" => "performRemove",
            "pkg-exists" => "performExistenceCheck",
            "channel-discover" => "channelDiscover",
            "channel-delete" => "channelDelete",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "PECL";
        $this->programDataFolder = "";
        $this->programNameMachine = "pecl"; // command and app dir name
        $this->programNameFriendly = "!PECL!!"; // 12 chars
        $this->programNameInstaller = "PECL";
        $this->statusCommand = "pecl" ;
        $this->initialize();
    }

    public function isInstalled($packageName) {
        if (!is_array($packageName)) { $packageName = array($packageName) ; }
        $passing = true ;
        foreach ($packageName as $package) {
            $out = $this->executeAndLoad("sudo pecl info {$package}") ;
            if (strpos($out, "Installed: (none)") != false) { $passing = false ; } }
        return $passing ;
    }

    public function installPackage($packageName, $version=null, $versionAccuracy=null) {
        $packageName = $this->getPackageName($packageName);
        if (!is_array($packageName)) { $packageName = array($packageName) ; }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (count($packageName) > 1 && ($version != null || $versionAccuracy != null) ) {
            $lmsg = "Multiple Packages were provided to the Packager {$this->programNameInstaller} at once with versions." ;
            $logging->log($lmsg) ;
            \BootStrap::setExitCode(1) ;
            return false ; }
        foreach ($packageName as $package) {
            if (!is_null($version)) {
                 $versionToInstall = "" ;
            }
            $out = $this->executeAndOutput("sudo pecl install $package -y");
            if (strpos($out, "Setting up $package") != false) {
                $logging->log("Adding Package $package from the Packager {$this->programNameInstaller} executed correctly") ; }
            else if (strpos($out, "is already the newest version.") != false) {
                $ltext  = "Package $package from the Packager {$this->programNameInstaller} is " ;
                $ltext .= "already installed, so not installing." ;
                $logging->log($ltext) ; }
            else if (strpos($out, "ldconfig deferred processing now taking place") == false) {
                $logging->log("Adding Package $package from the Packager {$this->programNameInstaller} did not execute correctly") ;
                return false ; } }
        return true ;
    }

    public function removePackage($packageName) {
        $packageName = $this->getPackageName($packageName);
        $out = $this->executeAndOutput("sudo pecl-get remove $packageName -y --force-yes");
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
        $out = $this->executeAndOutput("sudo pecl-get update -y");
        if (strpos($out, "Done") != false) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Updating the Packager {$this->programNameInstaller} did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function versionCompatible($autopilot = null) {
        $out = $this->executeAndOutput("sudo pecl-get update -y");
        if (strpos($out, "Done") != false) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Updating the Packager {$this->programNameInstaller} did not execute correctly") ;
            return false ; }
        return true ;
    }

}