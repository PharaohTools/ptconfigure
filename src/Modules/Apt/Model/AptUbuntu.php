<?php

Namespace Model;

class AptUbuntu extends BasePackager {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array(array("11.04", "+")) ;
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
        if (!is_array($packageName)) { $packageName = array($packageName) ; }
        $passing = true ;
        foreach ($packageName as $package) {
            $out = $this->executeAndLoad(SUDOPREFIX."apt-cache policy {$package}") ;
            if (strpos($out, "Installed: (none)") != false) { $passing = false ; } }
        return $passing ;
    }

    public function installPackage($packageName, $version=null, $versionAccuracy=null) {
        $packageName = $this->getPackageName($packageName);
        if (!is_array($packageName)) {
               $packageName = array($packageName) ;
            }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (count($packageName) > 1 && ($version != null || $versionAccuracy != null) ) {
            // @todo multiple versioned packages should work!!
            $lmsg = "Multiple Packages were provided to the Packager {$this->programNameInstaller} at once with versions." ;
            $logging->log($lmsg, $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ; }
        foreach ($packageName as $package) {
            if (!is_null($version)) {
                 $versionToInstall = "" ;
            }
            $out = $this->executeAndOutput(SUDOPREFIX."apt-get -qq install $package -y > /dev/null ");
            if (strpos($out, "Setting up $package") !== false) {
                $logging->log("Adding Package $package from the Packager {$this->programNameInstaller} executed correctly", $this->getModuleName()) ; }
            else if (strpos($package, " ") !== false) {
                $packageNames = explode(" ", $package) ;
                foreach ($packageNames as $onePackageName) {
                    if (strpos($out, "Setting up $onePackageName") !== false) {
                        $logging->log("Adding Package $onePackageName from the Packager {$this->programNameInstaller} executed correctly", $this->getModuleName()) ; }
                } }
            else if (strpos($out, "is already the newest version.") !== false) {
                $ltext  = "Package $package from the Packager {$this->programNameInstaller} is " ;
                $ltext .= "already installed, so not installing." ;
                $logging->log($ltext, $this->getModuleName()) ; }
//            else if (strpos($out, "ldconfig deferred processing now taking place") === false) {
//                $logging->log("Adding Package $package from the Packager {$this->programNameInstaller} did not execute correctly", $this->getModuleName()) ;
//                return false ; }
        }
        return true ;
    }

    public function removePackage($packageName) {
        $packageName = $this->getPackageName($packageName);
        $out = $this->executeAndOutput(SUDOPREFIX."apt-get -qq remove $packageName -y > /dev/null ");
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ( strpos($out, "The following packages will be REMOVED") != false ) {
            $logging->log("Removed Package {$packageName} from the Packager {$this->programNameInstaller}", $this->getModuleName()) ;
            return false ; }
        else if ( strpos($out, "is not installed, so not removed") != false) {
            $ltext  = "Package {$packageName} from the Packager {$this->programNameInstaller} is " ;
            $ltext .= "not installed, so not removed." ;
            $logging->log($ltext, $this->getModuleName()) ;
            return false ; }
        return true ;
    }

    public function update() {
        $out = $this->executeAndOutput(SUDOPREFIX."apt-get -qq update -y > /dev/null ");
        if (strpos($out, "Done") != false) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Updating the Packager {$this->programNameInstaller} did not execute correctly", $this->getModuleName()) ;
            return false ; }
        return true ;
    }

    public function versionCompatible() {
        $out = $this->executeAndOutput(SUDOPREFIX."apt-get -qq update -y > /dev/null ");
        if (strpos($out, "Done") != false) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Updating the Packager {$this->programNameInstaller} did not execute correctly", $this->getModuleName()) ;
            return false ; }
        return true ;
    }

}