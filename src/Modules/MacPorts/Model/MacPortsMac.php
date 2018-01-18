<?php

Namespace Model;

class MacPortsMac extends BasePackager {

    // Compatibility
    public $os = array("Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array(array("10.4", "+")) ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $packagerName = "MacPorts";

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "MacPorts";
        $this->programDataFolder = "";
        $this->programNameMachine = "macPorts"; // command and app dir name
        $this->programNameFriendly = "!MacPorts!!"; // 12 chars
        $this->programNameInstaller = "MacPorts";
        $this->statusCommand = ". /etc/profile
            port version " ;
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "installMacPorts", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "ensureBashProfilePaths", "params" => array()) ),
        );
        $this->uninstallCommands = array(
            array("command"=> array(
                SUDOPREFIX." rm -rf /opt/local",
                SUDOPREFIX." rm -rf /Applications/DarwinPorts",
                SUDOPREFIX." rm -rf /Applications/MacPorts",
                SUDOPREFIX." rm -rf /Library/LaunchDaemons/org.macports.*",
                SUDOPREFIX." rm -rf /Library/Receipts/DarwinPorts*.pkg",
                SUDOPREFIX." rm -rf /Library/Receipts/MacPorts*.pkg",
                SUDOPREFIX." rm -rf /Library/StartupItems/DarwinPortsStartup",
                SUDOPREFIX." rm -rf /Library/Tcl/darwinports1.0",
                SUDOPREFIX." rm -rf /Library/Tcl/macports1.0",
//                SUDOPREFIX." rm -rf ~/.macports",
            ),),);
        $this->initialize();
    }

    public function ensureXCode() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Ensuring XCode Dependency", $this->getModuleName()) ;
        $xcodeFactory = new \Model\XCode() ;
        $xcode = $xcodeFactory->getModel($this->params) ;
        $stat = $xcode->askStatus() ;
        if ($stat == true) {
            $res[] = true ; }
        else {
            $res[] = $xcode->ensureInstalled() ; }
        return in_array(false, $res)==false ;
    }

    public function installMacPorts() {
        $system = new \Model\SystemDetectionAllOS() ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $msg = "Found OSx version {$system->version}" ;
        $logging->log($msg, $this->getModuleName()) ;
        $pos = strpos($system->version, '.', strpos($system->version, '.')+1);
        $version = substr($system->version, 0, $pos) ;
        switch ($version) {
            case "10.4" :
                $filename = "MacPorts-2.3.3-10.4-Tiger.dmg" ;
                break ;
            case "10.5" :
                $filename = "MacPorts-2.3.3-10.5-Leopard.dmg" ;
                break ;
            case "10.6" :
                $filename = "MacPorts-2.3.3-10.6-SnowLeopard.pkg" ;
                break ;
            case "10.7" :
                $filename = "MacPorts-2.3.3-10.7-Lion.pkg" ;
                break ;
            case "10.8" :
                $filename = "MacPorts-2.3.3-10.8-MountainLion.pkg" ;
                break ;
            case "10.9" :
                $filename = "MacPorts-2.3.4-10.9-Mavericks.pkg" ;
                break ;
            case "10.10" :
                $filename = "MacPorts-2.3.4-10.10-Yosemite.pkg" ;
                break ;
            case "10.11" :
                $filename = "MacPorts-2.3.4-10.11-ElCapitan.pkg" ;
                break ;
            case "10.12" :
                $filename = "MacPorts-2.4.2-10.12-Sierra.pkg" ;
                break ;
            case "10.13" :
                $filename = "MacPorts-2.4.2-10.13-HighSierra.pkg" ;
                break ;
            default :
                $filename = false ;
                break ; }
        if ($filename == false) {
            $logging->log("Unable to find correct version of MacPorts to install for this OSx version {$version}", $this->getModuleName()) ;
            return false ; }
        $url = 'https://distfiles.macports.org/MacPorts/' ;
        $msg = "Downloading file {$filename} from $url" ;
        $logging->log($msg, $this->getModuleName()) ;
        $curlCommand = "curl {$url}{$filename} -o /tmp/{$filename}" ;
        $this->executeAndOutput($curlCommand) ;
        if (strpos($filename, ".pkg") !== false) {
            $logging->log("Performing .pkg install", $this->getModuleName()) ;
            $comm = SUDOPREFIX."installer -pkg /tmp/{$filename} -target /" ;
            $rc3 = $this->executeAndGetReturnCode($comm, true, false) ;
            $ret_stat = ($rc3["rc"] == 0) ? true : false ; // in_array(false, array($rc3["rc"])) ;
            return $ret_stat ; }
        else if (strpos($filename, ".dmg") !== false) {
            $logging->log("Performing .dmg install", $this->getModuleName()) ;
            $comm = SUDOPREFIX."hdiutil attach /tmp/{$filename}" ;
            $rc1 = $this->executeAndGetReturnCode($comm, true, false) ;
            $comm = SUDOPREFIX.'installer -pkg /Volumes/MacPorts-2.3.3/MacPorts-2.3.3.pkg -target /' ;
            $rc2 = $this->executeAndGetReturnCode($comm, true, false) ;
            $comm = SUDOPREFIX."hdiutil detach /Volumes/MacPorts-2.3.3/MacPorts-2.3.3.pkg" ;
            $rc3 = $this->executeAndGetReturnCode($comm, true, false) ;
            $comm = SUDOPREFIX.' port selfupdate' ;
            $rc4 = $this->executeAndGetReturnCode($comm, true, false) ;
            $is_false = in_array(false, array($rc1["rc"], $rc2["rc"], $rc3["rc"], $rc4["rc"])) ;
            return ($is_false) ? false : true ; }
        else {
            $logging->log("Filename error for MacPorts download", $this->getModuleName()) ;
            return false ; }
    }

    protected function getPathSetString() {
        return ' PATH=/opt/local/bin:/opt/local/sbin:$PATH ' ;
    }

    public function isInstalled($packageName) {
        $mpx = $this->getPathSetString() ;
        if (!is_array($packageName)) { $packageName = array($packageName) ; }
        $passing = true ;
        foreach ($packageName as $package) {
            $out = $this->executeAndLoad(SUDOPREFIX.$mpx."port installed") ;
            if (strpos($out, $package) == false) { $passing = false ; } }
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
            $logging->log($lmsg, $this->getModuleName()) ; ;
            \BootStrap::setExitCode(1) ;
            return false ; }
        foreach ($packageName as $package) {
            $mpx = $this->getPathSetString() ;
            if (!is_null($version)) {
                 $versionToInstall = "" ;
            }
            $out = $this->executeAndOutput(SUDOPREFIX.$mpx."port install $package -y");
            if (strpos($out, "Setting up $package") != false) {
                $logging->log("Adding Package $package from the Packager {$this->programNameInstaller} executed correctly", $this->getModuleName()) ; }
            else if (strpos($out, "is already the newest version.") != false) {
                $ltext  = "Package $package from the Packager {$this->programNameInstaller} is " ;
                $ltext .= "already installed, so not installing." ;
                $logging->log($ltext, $this->getModuleName()) ; }
            else if (strpos($out, "ldconfig deferred processing now taking place") == false) {
                $logging->log("Adding Package $package from the Packager {$this->programNameInstaller} did not execute correctly", $this->getModuleName()) ;
                return false ; } }
        return true ;
    }

    public function removePackage($packageName) {
        $mpx = $this->getPathSetString() ;
        $packageName = $this->getPackageName($packageName);
        $out = $this->executeAndOutput(SUDOPREFIX.$mpx."port uninstall $packageName -y");
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
        $mpx = $this->getPathSetString() ;
        $out = $this->executeAndGetReturnCode(SUDOPREFIX.$mpx."port selfupdate", true, true);
        if (strpos($out, "The ports tree has been updated.") == false) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Updating the Packager {$this->programNameInstaller} did not execute correctly", $this->getModuleName()) ;
            return false ; }
        return true ;
    }

    public function versionCompatible() {
//        $out = $this->executeAndOutput(SUDOPREFIX."macPorts-get update -y");
//        if (strpos($out, "Done") != false) {
//            $loggingFactory = new \Model\Logging();
//            $logging = $loggingFactory->getModel($this->params);
//            $logging->log("Updating the Packager {$this->programNameInstaller} did not execute correctly", $this->getModuleName()) ;
//            return false ; }
//        return true ;
    }

    public function ensureBashProfilePaths() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Ensuring Environment Variables are set system wide", $this->getModuleName()) ;
        $profileLocation = '/etc/profile' ;
        $fileFactory = new \Model\File();
        $lines = array(
            'export PATH=$PATH:/opt/local/bin',
            'export MANPATH=$MANPATH:/opt/local/share/man',
            'export INFOPATH=$INFOPATH:/opt/local/share/info' );
        $params["file"] = $profileLocation ;
        foreach ($lines as $line) {
            $params["search"] = $line ;
            $file = $fileFactory->getModel($params) ;
            $file->performShouldHaveLine() ; }
    }


}