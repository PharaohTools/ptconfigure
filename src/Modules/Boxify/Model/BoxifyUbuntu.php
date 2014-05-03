<?php

Namespace Model;

class BoxifyUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "Boxify";
        $this->programNameMachine = "boxify"; // command and app dir name
        $this->programNameFriendly = "Boxify!"; // 12 chars
        $this->programNameInstaller = "Boxify your Environments";
        $this->initialize();
    }

    public function performBoxAdd($providerName = null, $environmentName = null, $boxAmount = null) {
        $this->setEnvironment($environmentName);
        $this->setProvider($providerName);
        $this->setBoxAmount($boxAmount);
        return $this->addBox();
    }

    public function performBoxRemove($providerName = null, $environmentName = null) {
        $this->setEnvironment($environmentName);
        $this->setProvider($providerName);
        return $this->removeBoxes();
    }

    public function performBoxDestroy($providerName = null, $environmentName = null) {
        $this->setEnvironment($environmentName);
        $this->setProvider($providerName);
        return $this->destroyBoxes();
    }

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
            $this->boxAmount = self::askForInput("Enter number of Boxes:", true); }
    }

    protected function addBox() {
        $provider = $this->getProvider();
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $returns = array() ;
        for($i=1; $i<=$this->boxAmount; $i++) {
            $logging->log("Adding Box Number $i") ;
            $result = $provider->addBox() ;
            if ($result == true) {
                // @todo do we need this
                $this->setEnvironmentStatusInCleovars($oneBox, true) ; } ;
            $returns[] = $result ; }
        return (in_array(false, $returns)) ? false : true ;
    }

    protected function removeBoxes() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        foreach($this->boxAmount as $oneBox) {
            $logging->log("Removing Box $oneBox") ;
            $this->setEnvironmentStatusInCleovars($oneBox, false) ; }
        return true ;
    }

    protected function destroyBoxes() {
        $provider = $this->getProvider("BoxDestroy");
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Destroying Boxes in environment $this->environmentName") ;
        $return = $provider->destroyBox() ;
        return $return ;
    }

    protected function getProvider($modGroup = "BoxAdd") {
        $infoObjects = \Core\AutoLoader::getInfoObjects();
        $allProviders = array();
        foreach($infoObjects as $infoObject) {
            if ( method_exists($infoObject, "boxProviderName") ) {
                $allProviders[] = $infoObject->boxProviderName(); } }
        foreach($allProviders as $oneProvider) {
            if ( (isset($this->providerName) && $this->providerName == $oneProvider) ) {
                $className = '\Model\\'.$oneProvider ;
                $providerFactory = new $className();
                $provider = $providerFactory->getModel($this->params, $modGroup);
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