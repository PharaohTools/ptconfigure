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
    protected $version ;
    protected $versionAccuracy ;
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

    public function performPackageInstall($packagerName=null, $packageName=null, $module=null, $version=null, $versionAccuracy=null) {
        $this->setPackage($packageName);
        $this->setPackager($packagerName);
        $this->setModule($module);
        $this->setVersion($version) ;
        $this->setVersionAccuracy($versionAccuracy) ;
        return $this->installPackages();
    }

    public function performPackageEnsure($packagerName=null, $packageName=null, $module=null, $version=null, $versionAccuracy=null) {
        $this->setPackage($packageName);
        $this->setPackager($packagerName);
        $this->setModule($module);
        $this->setVersion($version) ;
        $this->setVersionAccuracy($versionAccuracy) ;
        return $this->ensureInstalled();
    }

    public function performPackageRemove($packagerName=null, $packageName=null, $module=null, $version=null, $versionAccuracy=null) {
        $this->setPackage($packageName);
        $this->setPackager($packagerName);
        $this->setModule($module);
        $this->setVersion($version) ;
        $this->setVersionAccuracy($versionAccuracy) ;
        return $this->removePackages();
    }

    public function performPackageExistenceCheck($packagerName=null, $packageName=null, $module=null, $version=null, $versionAccuracy=null) {
        $this->setPackage($packageName);
        $this->setPackager($packagerName);
        $this->setModule($module);
        $this->setVersion($version) ;
        $this->setVersionAccuracy($versionAccuracy) ;
        return $this->isInstalled();
    }

    public function setPackage($packageName = null) {
        if (isset($packageName)) {
            $this->packageName = $packageName; }
        else if (isset($this->params["packagename"])) {
            $this->packageName = $this->params["packagename"]; }
        else if (isset($this->params["package-name"])) {
            $this->packageName = $this->params["package-name"]; }
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
        else {
            $this->packagerName = self::askForInput("Enter Packager Name:", true); }
    }

    public function setVersion($version = null) {
        if (isset($version)) {
            $this->version = $version; }
        else if (isset($this->params["version"])) {
            $this->version = $this->params["version"]; }
    }

    public function setVersionAccuracy($versionAccuracy = null) {
        if (isset($versionAccuracy)) {
            $this->versionAccuracy = $versionAccuracy; }
        else if (isset($this->params["version-accuracy"])) {
            $this->versionAccuracy = $this->params["version-accuracy"]; }
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

    protected function installPackages() {
        $packager = $this->getPackager();
        if (!is_array($this->packageName)) { $this->packageName = array($this->packageName); }
        $returns = array() ;
        foreach($this->packageName as $onePackage) {
            $result = $packager->installPackage($onePackage) ;
            if ($result == true) { $this->setPackageStatusInCleovars($onePackage, true) ; } ;
            $returns[] = $result ; }
        return (in_array(false, $returns)) ? false : true ;
    }

    protected function removePackages() {
        $packager = $this->getPackager();
        if (!is_array($this->packageName)) { $this->packageName = array($this->packageName); }
        $returns = array() ;
        $consoleFactory = new \Model\Console();
        $console = $consoleFactory->getModel($this->params);
        foreach($this->packageName as $onePackage) {
            $packageIsRequired = $this->packageIsRequired($onePackage) ;
            if ($packageIsRequired) {
                $moduleString = implode(', ', $packageIsRequired) ;
                $console->log("Not removing Package $onePackage, it's required by these Modules: $moduleString") ; }
            else {
                $console->log("Removing Package $onePackage") ;
                $returns[] = $packager->removePackage($onePackage) ; } }
        return (in_array(false, $returns)) ? false : true ;
    }

    public function ensureInstalled() {
        if ($this->isInstalled()==false) { $this->installPackages(); }
        else {
            $this->setPackageStatusInCleovars($this->packageName, true);
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            if (is_array($this->packageName) ) {
                $lText  = "Packages ".implode(", ", $this->packageName) ;
                $lText .= " from the Packager {$this->packagerName} are already installed" ; }
            else {
                $lText = "Package {$this->packageName} from the Packager {$this->packagerName} is already installed" ; }
            $console->log($lText); }
        return $this;
    }

    public function isInstalled() {
        $packager = $this->getPackager() ;
        if ($packager->isInstalled($this->packageName)) { return true ; }
        return false;
    }

    public function packageIsRequired($packageName) {
        $installedPackages = \Model\AppConfig::getAppVariable("installed-packages") ;
        $modsWithPackages = array_keys($installedPackages);
        $modsRequiring = array() ;
        foreach ($modsWithPackages as $modWithPackages) {
            if ($installedPackages[$modWithPackages][$this->packagerName][$packageName] == true) {
                 $modsRequiring[] = $modWithPackages ; } }
        $finalModsRequiring = array() ;
        foreach ($modsRequiring as $modRequiring) {
            if ($modRequiring != $this->moduleName) { $finalModsRequiring[] = $modRequiring ; } }
        return (count($finalModsRequiring)>0) ? $finalModsRequiring : false ;
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

    public function setPackageStatusInCleovars($packageName, $bool) {
        if ($bool == true) {
            if (is_array($packageName)) {
                foreach ($packageName as $onePackageName) { $this->addPackageToCleovars($onePackageName) ; } }
            else { $this->addPackageToCleovars($packageName) ; } }
        else {
            if (is_array($packageName)) {
                foreach ($packageName as $onePackageName) { $this->removePackageFromCleovars($onePackageName) ; } }
            else {
                $this->removePackageFromCleovars($packageName) ; } }
    }

    protected function addPackageToCleovars($packageName) {
        $installedPackages = \Model\AppConfig::getAppVariable("installed-packages") ;
        $installedPackages[$this->moduleName][$this->packagerName][$packageName] = true ;
        \Model\AppConfig::setAppVariable("installed-packages", $installedPackages) ;
    }

    protected function removePackageFromCleovars($packageName) {
        $installedPackages = \Model\AppConfig::getAppVariable("installed-packages") ;
        unset($installedPackages[$this->moduleName][$this->packagerName][$packageName]) ;
        \Model\AppConfig::setAppVariable("installed-packages", $installedPackages) ;
    }

}