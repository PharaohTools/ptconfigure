<?php

Namespace Model;

class PackageManagerUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $packageName ;
    protected $packagerName ;
    protected $moduleName ;
    protected $requestingModule ;
    protected $actionsToMethods =
        array(
            "pkg-install" => "performPackageInstall",
            "pkg-ensure" => "performPackageEnsure",
            "pkg-remove" => "performPackageRemove",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "PackageManager";
        $this->programNameMachine = "packagemanager"; // command and app dir name
        $this->programNameFriendly = "Package Mgr!"; // 12 chars
        $this->programNameInstaller = "Package Manager";
        $this->initialize();
    }

    public function performPackageInstall($packagerName, $packageName, $module) {
        $this->setPackage($packageName);
        $this->setPackager($packagerName);
        $this->setModule($module);
        return $this->installPackages();
    }

    public function performPackageEnsure($packagerName, $packageName, $module) {
        $this->setPackage($packageName);
        $this->setPackager($packagerName);
        $this->setModule($module);
        return $this->ensureInstalled();
    }

    public function performPackageRemove($packagerName, $packageName, $module) {
        $this->setPackage($packageName);
        $this->setPackager($packagerName);
        $this->setModule($module);
        return $this->removePackages();
    }

    public function performPackageExistenceCheck($packagerName, $packageName, $module) {
        $this->setPackage($packageName);
        $this->setPackager($packagerName);
        $this->setModule($module);
        return $this->isInstalled();
    }

    public function setPackage($packageName = null) {
        if (isset($packageName)) {
            $this->packageName = $packageName; }
        else if (isset($this->params["packagename"])) {
            $this->packageName = $this->params["packagename"]; }
        else if (isset($this->params["package-name"])) {
            $this->packageName = $this->params["package-name"]; }
        else if (isset($autopilot["packagename"])) {
            $this->packageName = $autopilot["packagename"]; }
        else if (isset($autopilot["package-name"])) {
            $this->packageName = $autopilot["package-name"]; }
        else {
            $this->packageName = self::askForInput("Enter Package Name:", true); }
    }

    public function setPackager($packagerName = null) {
        if (isset($packagerName)) {
            $this->packagerName = $packagerName; }
        else if (isset($this->params["packagername"])) {
            $this->packagerName = $this->params["packagername"]; }
        else if (isset($this->params["packager-name"])) {
            $this->packagerName = $this->params["packager-name"]; }
        else if (isset($autopilot["packagername"])) {
            $this->packagerName = $autopilot["packagername"]; }
        else if (isset($autopilot["packager-name"])) {
            $this->packagerName = $autopilot["packager-name"]; }
        else {
            $this->packagerName = self::askForInput("Enter Packager Name:", true); }
    }

    public function setModule($moduleName = null) {
        if (isset($moduleName) && is_string($moduleName)) {
            $this->moduleName = $moduleName; }
        else if (isset($moduleName) && is_object($moduleName)) {
            $this->moduleName = $moduleName->getModuleName(); }
        else if (isset($this->params["force-modulename"])) {
            $this->moduleName = $this->params["force-modulename"]; }
        else if (isset($this->params["force-module-name"])) {
            $this->moduleName = $this->params["force-module-name"]; }
        else {
            $this->moduleName = "NoModuleAssociated" ; }
    }

    protected function installPackages($autopilot = null) {
        $packager = $this->getPackager();
        if (!is_array($this->packageName)) { $this->packageName = array($this->packageName); }
        $returns = array() ;
        foreach($this->packageName as $onePackage) {
            $result = $packager->installPackage($onePackage) ;
            if ($result == true) { $this->setPackageStatusInPapyrus($onePackage, true) ; } ;
            $returns[] = $result ; }
        return (in_array(false, $returns)) ? false : true ;
    }

    protected function removePackages($autopilot = null) {
        $packager = $this->getPackager();
        if (!is_array($this->packageName)) { $this->packageName = array($this->packageName); }
        $returns = array() ;
        foreach($this->packageName as $onePackage) { $returns[] = $packager->removePackage($onePackage); }
        return (in_array(false, $returns)) ? false : true ;
    }

    public function ensureInstalled() {
        if ($this->isInstalled()==false) { $this->installPackages($autopilot = null); }
        else {
            // @todo need to write to papyrus
            $this->setPackageStatusInPapyrus($this->packageName, true);
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log("Package {$this->packageName} from the Packager {$this->packagerName} is already installed"); }
        return $this;
    }

    public function isInstalled() {
        $packager = $this->getPackager() ;
        if ($packager->isInstalled($this->packageName)) { return true ; }
        return false;
    }

    protected function getPackager() {
        $infoObjects = \Core\AutoLoader::getInfoObjects();
        $allPackagers = array();
        foreach($infoObjects as $infoObject) {
            if ( method_exists($infoObject, "packagerName") ) {
                $allPackagers[] = $infoObject->packagerName(); } }
        foreach($allPackagers as $onePackager) {
            if ( (isset($this->packagerName) && $this->packagerName == $onePackager) ) {
                $className = '\Model\\'.$onePackager ;
                $pkgrFactory = new $className();
                $pkgr = $pkgrFactory->getModel($this->params);
                return $pkgr ; } }
        return false ;
    }

    public function setPackageStatusInPapyrus($packageName, $bool) {
        if ($bool == true) {
            if (is_array($packageName)) {
                foreach ($packageName as $onePackageName) { $this->addPackageToPapyrus($onePackageName) ; } }
            else { $this->addPackageToPapyrus($packageName) ; } }
        else {
            if (is_array($packageName)) {
                foreach ($packageName as $onePackageName) { $this->removePackageFromPapyrus($onePackageName) ; } }
            else {
                $this->removePackageFromPapyrus($packageName) ; } }
    }

    protected function addPackageToPapyrus($packageName) {
        //@todo check this works then remove comments
        // get in memory version of the installed-packages section of the papyrus
        $installedPackages = \Model\AppConfig::getAppVariable("installed-packages") ;
        // set the following multi dimensional array to it ["Module"]["Packager"]["Package"]
        $installedPackages[$this->moduleName][$this->packagerName][$packageName] = true ;
        // again set the installed-packages section of the papyrus
        \Model\AppConfig::setAppVariable("installed-packages", $installedPackages) ;
    }

    protected function removePackageFromPapyrus($packageName) {
        //@todo check this works then remove comments
        // get in memory version of the installed-packages section of the papyrus
        $installedPackages = \Model\AppConfig::getAppVariable("installed-packages") ;
        // remove the following multi dimensional array from it ["Module"]["Packager"]["Package"]
        unset($installedPackages[$this->moduleName][$this->packagerName][$packageName]) ;
        // again set the installed-packages section of the papyrus
        \Model\AppConfig::setAppVariable("installed-packages", $installedPackages) ;
    }

}