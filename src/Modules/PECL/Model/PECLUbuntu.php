<?php

Namespace Model;

class PECLUbuntu extends BasePackager {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array(array("11.04", "+")) ;
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
            $out = $this->executeAndLoad(SUDOPREFIX."pecl info {$package}") ;
            if (strpos($out, "Last Modified") == false) { $passing = false ; } }
        return $passing ;
    }

    public function installPackage($packageName, $version=null, $versionAccuracy=null) {
        $packageName = $this->getPackageName($packageName);
        if (!is_array($packageName)) { $packageName = array($packageName) ; }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (count($packageName) > 1 && ($version != null || $versionAccuracy != null) ) {
            $lmsg = "Multiple Packages were provided to the Packager {$this->programNameInstaller} at once with versions." ;
            $logging->log($lmsg, $this->getModuleName()) ;
            \Core\BootStrap::setExitCode(1) ;
            return false ; }
        foreach ($packageName as $package) {
            if (!is_null($version)) { $versionToInstall = "" ; }
            $yesprefix = "" ;
            if (substr($package, strlen($package)-5)=="-beta") { $yesprefix = 'yes "" | ' ; }
            $out = $this->executeAndOutput($yesprefix.SUDOPREFIX."pecl install $package");
            if (strpos($out, "install ok: $package") != false) {
                $logging->log("Adding Package $package from the Packager {$this->programNameInstaller} executed correctly", $this->getModuleName()) ; }
            else if (strpos($out, "is already the newest version.") != false) {
                $ltext  = "Package $package from the Packager {$this->programNameInstaller} is " ;
                $ltext .= "already installed, so not installing." ;
                $logging->log($ltext, $this->getModuleName()) ; }
            else if (strpos($out, "ERROR:") == true) {
                $logging->log("Adding Package $package from the Packager {$this->programNameInstaller} did not execute correctly", $this->getModuleName()) ;
                return false ; } }
        return true ;
    }

    public function removePackage($packageName) {
        $packageName = $this->getPackageName($packageName);
        $out = $this->executeAndOutput(SUDOPREFIX."pecl uninstall $packageName");
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ( strpos($out, "uninstall ok:") != false ) {
            $logging->log("Removed Package {$packageName} from the Packager {$this->programNameInstaller}") ;
            return false ; }
        else if ( strpos($out, "not installed") != false) {
            $ltext  = "Package {$packageName} from the Packager {$this->programNameInstaller} is " ;
            $ltext .= "not installed, so not removed." ;
            $logging->log($ltext) ;
            return false ; }
        return true ;
    }

    public function update($autopilot = null) {
        $out = $this->executeAndOutput(SUDOPREFIX."pecl update");
        if (strpos($out, "Done") != false) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Updating the Packager {$this->programNameInstaller} did not execute correctly", $this->getModuleName()) ;
            return false ; }
        return true ;
    }

    public function versionCompatible($autopilot = null) {
        $out = $this->executeAndOutput(SUDOPREFIX."pecl-get update -y");
        if (strpos($out, "Done") != false) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Updating the Packager {$this->programNameInstaller} did not execute correctly", $this->getModuleName()) ;
            return false ; }
        return true ;
    }

}