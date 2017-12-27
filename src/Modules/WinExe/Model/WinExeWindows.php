<?php

Namespace Model;

class WinExeWindows extends BasePackager {

    // Compatibility
    public $os = array("Windows", "WINNT") ;
    public $linuxType = array('any') ;
    public $distros = array('any');
    public $versions = array('any') ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $packagerName = "WinExe";

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "WinExe";
        $this->programDataFolder = "";
        $this->programNameMachine = "winexe"; // command and app dir name
        $this->programNameFriendly = "!WinExe!!"; // 12 chars
        $this->programNameInstaller = "WinExe";
        $this->statusCommand = "winexe-get" ;
        $this->initialize();
    }

    public function isInstalled($packageName, $override=null) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $c1 = 'reg query "HKLM\SOFTWARE\Microsoft\Windows\CurrentVersion\Uninstall" /s | findstr /B ".*DisplayName" ' ;
        $one_str = self::executeAndLoad($c1) ;
        $c2 = 'reg query "HKLM\SOFTWARE\Wow6432Node\Microsoft\Windows\CurrentVersion\Uninstall" /s | findstr /B ".*DisplayName" ';
        $two_str = self::executeAndLoad($c2) ;
        $c3 = 'wmic /PRIVILEGES:enable product get name,version ';
        $three_str = self::executeAndLoad($c3) ;
        $full_str = $one_str."\n".$two_str."\n".$three_str ;
        $installed_apps = explode("\n", $full_str) ;
        # var_dump('inst app', $installed_apps) ;
        foreach ($installed_apps as $installed_app) {
            # $installed_app = substr($installed_app, 29) ;
            # var_dump("One app", $installed_app, $packageName, $override, (strpos($installed_app, $override) !== false), (strpos($installed_app, $packageName) !== false)) ;
            if (!is_null($override)) {
                $is_found = (strpos($installed_app, $override) !== false) ;
            } else {
                $is_found = (strpos($installed_app, $packageName) !== false) ;
            }

            if ($is_found === true) {
                $lmsg = "WinExeWindows package {$packageName} is installed" ;
                $logging->log($lmsg, $this->getModuleName()) ;
                return true ;
            }
        }
        $lmsg = "WinExeWindows package {$packageName} is not installed" ;
        $logging->log($lmsg, $this->getModuleName()) ;
        return false ;
    }

    public function installPackage($packageName, $version=null, $versionAccuracy=null, $requestingModel=null) {
        $packageName = $this->getPackageName($packageName);
        if (!is_array($packageName)) { $packageName = array($packageName) ; }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (count($packageName) > 1 && ($version != null || $versionAccuracy != null) ) {
            // @todo multiple versioned packages should work!!
            $lmsg = "Multiple Packages were provided to the Packager Windows Executable at once with versions." ;
            $logging->log($lmsg) ;
            \BootStrap::setExitCode(1) ;
            return false ; }
        foreach ($packageName as $package) {
            if (!is_null($version)) {
                $versionToInstall = "" ;
            }
            # var_dump('WinExeWindows installPackage 1 ') ;
            $requestingModel->askForVersion() ;
            $requestingModel->setPackageUrl() ;
            # var_dump('WinExeWindows installPackage 2 ', $requestingModel) ;
            $temp_exe = $requestingModel->packageDownload($requestingModel->packageUrl) ;
            $install_command = $temp_exe. ' '.$requestingModel->exeInstallFlags ;
            # var_dump('WinExeWindows installPackage 2 install_command  ', $install_command) ;
            $out = $this->executeAndOutput($install_command) ;
            $search_string = $requestingModel->getPackageSearchString() ;
            # var_dump('WinExeWindows installPackage 3 ', $search_string, $install_command) ;
            if ($this->isInstalled($search_string) != false) {
                $logging->log("Adding Package $package from the Packager Windows Executable executed correctly") ; }
            else {
                $logging->log("Adding Package $package from the Packager Windows Executable did not execute correctly") ;
                return false ; } }
        return true ;
    }

    public function removePackage($packageName) {
        $packageName = $this->getPackageName($packageName);
        $out = $this->executeAndOutput("winexe-get remove $packageName -y --force-yes");
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ( strpos($out, "The following packages will be REMOVED") != false ) {
            $logging->log("Removed Package {$packageName} from the Packager Windows Executable") ;
            return false ; }
        else if ( strpos($out, "is not installed, so not removed") != false) {
            $ltext  = "Package {$packageName} from the Packager Windows Executable is " ;
            $ltext .= "not installed, so not removed." ;
            $logging->log($ltext) ;
            return false ; }
        return true ;
    }

    public function update() {
        $out = $this->executeAndOutput("winexe-get update -y");
        if (strpos($out, "Done") != false) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Updating the Packager Windows Executable did not execute correctly") ;
            return false ; }
        return true ;
    }

    public function versionCompatible() {
        $out = $this->executeAndOutput("winexe-get update -y");
        if (strpos($out, "Done") != false) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Updating the Packager Windows Executable did not execute correctly") ;
            return false ; }
        return true ;
    }

}