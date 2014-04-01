<?php

Namespace Model;

class BoxManagerUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $environmentName ;
    protected $providerName ;
    protected $boxAmount ;
    protected $requestingModule ;
    protected $actionsToMethods =
        array(
            "box-add" => "performBoxAdd",
            "box-destroy" => "performBoxDestroy",
            "box-remove" => "performBoxRemove",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "BoxManager";
        $this->programNameMachine = "boxmanager"; // command and app dir name
        $this->programNameFriendly = "Box Mgr!"; // 12 chars
        $this->programNameInstaller = "Box Manager";
        $this->initialize();
    }

    public function performBoxAdd($providerName, $environmentName) {
        $this->setEnvironment($environmentName);
        $this->setProvider($providerName);
        return $this->installBoxes();
    }

//    public function performBoxEnsure($providerName, $environmentName) {
//        $this->setEnvironment($environmentName);
//        $this->setProvider($providerName);
//        return $this->ensureInstalled();
//    }

    public function performBoxRemove($providerName, $environmentName) {
        $this->setEnvironment($environmentName);
        $this->setProvider($providerName);
        return $this->removeBoxes();
    }

//    public function performBoxExistenceCheck($providerName, $environmentName) {
//        $this->setEnvironment($environmentName);
//        $this->setProvider($providerName);
//        return $this->isInstalled();
//    }

    public function setEnvironment($environmentName = null) {
        if (isset($environmentName)) {
            $this->environmentName = $environmentName; }
        else if (isset($this->params["environmentname"])) {
            $this->environmentName = $this->params["environmentname"]; }
        else if (isset($this->params["environment-name"])) {
            $this->environmentName = $this->params["environment-name"]; }
        else {
            $this->environmentName = self::askForInput("Enter Environment Name:", true); }
    }

    public function setProvider($providerName = null) {
        if (isset($providerName)) {
            $this->providerName = $providerName; }
        else if (isset($this->params["providername"])) {
            $this->providerName = $this->params["providername"]; }
        else if (isset($this->params["provider-name"])) {
            $this->providerName = $this->params["provider-name"]; }
        else {
            $this->providerName = self::askForInput("Enter Provider Name:", true); }
    }

    public function setBoxAmount($boxAmount = null) {
        if (isset($boxAmount)) {
            $this->boxAmount = $boxAmount; }
        else if (isset($this->params["boxamount"])) {
            $this->boxAmount = $this->params["boxamount"]; }
        else if (isset($this->params["box-amount"])) {
            $this->boxAmount = $this->params["box-amount"]; }
        else {
            $this->boxAmount = self::askForInput("Enter Box Amount:", true); }
    }

    protected function addBox() {
        $provider = $this->getProvider();
        $consoleFactory = new \Model\Console();
        $console = $consoleFactory->getModel($this->params);
        $returns = array() ;
        foreach($this->boxAmount as $oneBox) {
            $console->log("Adding Box $oneBox") ;
            $result = $provider->addBox($oneBox) ;
            if ($result == true) { $this->setEnvironmentStatusInCleovars($oneBox, true) ; } ;
            $returns[] = $result ; }
        return (in_array(false, $returns)) ? false : true ;
    }

    protected function removeBoxes() {
        $provider = $this->getProvider();
        if (!is_array($this->environmentName)) { $this->environmentName = array($this->environmentName); }
        $returns = array() ;
        $consoleFactory = new \Model\Console();
        $console = $consoleFactory->getModel($this->params);
        foreach($this->environmentName as $oneBox) {
            $environmentIsRequired = $this->environmentIsRequired($oneBox) ;
            if ($environmentIsRequired) {
                $moduleString = implode(', ', $environmentIsRequired) ;
                $console->log("Not removing Box $oneBox, it's required by these Modules: $moduleString") ; }
            else {
                $console->log("Removing Box $oneBox") ;
                $returns[] = $provider->removeBox($oneBox) ; } }
        return (in_array(false, $returns)) ? false : true ;
    }

    protected function getProvider() {
        $infoObjects = \Core\AutoLoader::getInfoObjects();
        $allProviders = array();
        foreach($infoObjects as $infoObject) {
            if ( method_exists($infoObject, "boxProviderName") ) {
                $allProviders[] = $infoObject->boxProviderName(); } }
        foreach($allProviders as $oneProvider) {
            if ( (isset($this->providerName) && $this->providerName == $oneProvider) ) {
                $className = '\Model\\'.$oneProvider ;
                $providerFactory = new $className();
                $provider = $providerFactory->getModel($this->params, "BoxAdd");
                return $provider ; } }
        return false ;
    }

    public function setEnvironmentStatusInCleovars($environmentName, $bool) {
        if ($bool == true) {
            if (is_array($environmentName)) {
                foreach ($environmentName as $oneBoxName) { $this->addBoxToCleovars($oneBoxName) ; } }
            else { $this->addBoxToCleovars($environmentName) ; } }
        else {
            if (is_array($environmentName)) {
                foreach ($environmentName as $oneBoxName) { $this->removeBoxFromCleovars($oneBoxName) ; } }
            else {
                $this->removeBoxFromCleovars($environmentName) ; } }
    }

    protected function addBoxToCleovars($environmentName) {
        $installedBoxes = \Model\AppConfig::getAppVariable("installed-environments") ;
        $installedBoxes[$this->moduleName][$this->providerName][$environmentName] = true ;
        \Model\AppConfig::setAppVariable("installed-environments", $installedBoxes) ;
    }

    protected function removeBoxFromCleovars($environmentName) {
        $installedBoxes = \Model\AppConfig::getAppVariable("installed-environments") ;
        unset($installedBoxes[$this->moduleName][$this->providerName][$environmentName]) ;
        \Model\AppConfig::setAppVariable("installed-environments", $installedBoxes) ;
    }

}