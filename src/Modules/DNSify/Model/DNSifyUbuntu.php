<?php

Namespace Model;

class DNSifyUbuntu extends BaseLinuxApp {

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
            "ensure-domain-exists" => "ensureDNSDomainExists",
            "ensure-domain-empty" => "ensureDNSDomainEmpty",
            "ensure-record-exists" => "ensureDNSRecordExists",
            "ensure-record-empty" => "ensureDNSRecordEmpty",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "DNSify";
        $this->programNameMachine = "dnsify"; // command and app dir name
        $this->programNameFriendly = "DNSify!"; // 12 chars
        $this->programNameInstaller = "DNSify your Environments";
        $this->initialize();
    }

    public function ensureDNSDomainExists($providerName = null, $environmentName = null, $boxAmount = null) {
        $this->setEnvironment($environmentName);
        $this->setProvider($providerName);
        $this->setDNSAmount($boxAmount);
        return $this->addDNS();
    }

    public function ensureDNSDomainEmpty($providerName = null, $environmentName = null) {
        $this->setEnvironment($environmentName);
        return $this->removeDNSes();
    }

    public function ensureDNSRecordExists($providerName = null, $environmentName = null, $boxAmount = null) {
        $this->setEnvironment($environmentName);
        $this->setProvider($providerName);
        $this->setDNSAmount($boxAmount);
        return $this->addDNS();
    }

    public function ensureDNSRecordEmpty($providerName = null, $environmentName = null) {
        $this->setEnvironment($environmentName);
        return $this->removeDNSes();
    }

    public function performDNSDestroy($providerName = null, $environmentName = null) {
        $this->setEnvironment($environmentName);
        $this->setProvider($providerName);
        return $this->destroyDNSes();
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

    public function setDNSAmount($boxAmount = null) {
        if (isset($boxAmount)) {
            $this->boxAmount = $boxAmount; }
        else if (isset($this->params["boxamount"])) {
            $this->boxAmount = $this->params["boxamount"]; }
        else if (isset($this->params["box-amount"])) {
            $this->boxAmount = $this->params["box-amount"]; }
        else {
            $this->boxAmount = self::askForInput("Enter number of DNSes:", true); }
    }

    protected function addDNS() {
        $provider = $this->getProvider();
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $returns = array() ;
        $logging->log("Adding DNSes") ;
        $result = $provider->addDNS() ;
        $returns[] = $result ;
        return (in_array(false, $returns)) ? false : true ;
    }

    protected function removeDNSes() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        foreach($this->boxAmount as $oneDNS) {
            $logging->log("Removing DNS $oneDNS") ;
            $this->setEnvironmentStatusInCleovars($oneDNS, false) ; }
        return true ;
    }

    protected function destroyDNSes() {
        $provider = $this->getProvider("DNSDestroy");
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Destroying DNSes in environment $this->environmentName") ;
        $return = $provider->destroyDNS() ;
        return $return ;
    }

    protected function getProvider($modGroup = "DNSAdd") {
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

}