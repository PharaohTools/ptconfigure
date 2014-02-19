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

    protected function performPackageInstall() {
        $this->setPackage();
        $this->setPackager();
        return $this->installOSPackage();
    }

    protected function performPackageEnsure() {
        $this->setPackage();
        $this->setPackager();
        return $this->ensureInstalled();
    }

    protected function performPackageRemove() {
        $this->setPackage();
        $this->setPackager();
        return $this->removeOSPackage();
    }

    protected function performPackageExistenceCheck() {
        $this->setPackage();
        $this->setPackager();
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

    public function installOSPackage($autopilot = null) {
        $packager = $this->getPackager();
        if (is_array($this->packageName)) {
            foreach($this->packageName as $onePackage) {
                $packager->installPackage($onePackage); } }
        else {
            $packager->installPackage($this->packageName); }
        return true ;
    }

    public function removeOSPackage($autopilot = null) {
        $packager = $this->getPackager();
        if (is_array($this->packageName)) {
            foreach($this->packageName as $onePackage) {
                $packager->removePackage($onePackage); } }
        else {
            $packager->removePackage($this->packageName); }
        return true ;
    }

    public function ensureInstalled() {
        if ($this->isInstalled()==false) { $this->installOSPackage($autopilot = null); }
        return $this;
    }

    public function isInstalled() {
        $packager = $this->getPackager() ;
        if ($packager->isInstalled($this->packageName)) { return true ; }
        return false;
    }

    public function getPackager() {
        $infoObjects = \Core\AutoLoader::getInfoObjects();
        $allPackagers = array();
        foreach($infoObjects as $infoObject) {
            if ( method_exists($infoObject, "packagerName") ) {
                $allPackagers[] = $infoObject->packagerName(); } }
        foreach($allPackagers as $onePackager) {
            $className = '\Model\\'.$onePackager ;
            $pkgrFactory = new $className();
            $pkgr = $pkgrFactory->getModel($this->params);
            return $pkgr ; }
        return false ;
    }

}