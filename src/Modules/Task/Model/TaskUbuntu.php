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
            "list-records" => "",
            "list-domains" => "",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "DNSify";
        $this->programNameMachine = "dnsify"; // command and app dir name
        $this->programNameFriendly = "DNSify!"; // 12 chars
        $this->programNameInstaller = "DNSify your Environments";
        $this->initialize();
    }

    public function ensureDNSDomainExists($providerName = null) {
        $this->setProvider($providerName);
        $this->getDomainName() ;
        return $this->addDNS("domain");
    }

    public function ensureDNSDomainEmpty($providerName = null, $environmentName = null) {
        $this->setProvider($providerName);
        $this->setEnvironment($environmentName);
        return $this->removeDNS();
    }

    public function ensureDNSRecordExists($providerName = null) {
        $this->setProvider($providerName);
        $this->getDomainName() ;
        $this->getRecordName() ;
        $this->getRecordType() ;
        $this->getRecordData() ;
        $this->getRecordTTL() ;
        return $this->addDNS("record");
    }

    public function ensureDNSRecordEmpty($providerName = null, $environmentName = null) {
        $this->setProvider($providerName);
        $this->setEnvironment($environmentName);
        return $this->removeDNS();
    }

    public function performDNSDestroy($providerName = null, $environmentName = null) {
        $this->setProvider($providerName);
        $this->setEnvironment($environmentName);
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


    protected function askForDomainAddExecute() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Ensure Rackspace Domains?';
        return self::askYesOrNo($question);
    }

    protected function askForRecordAddExecute() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Ensure Rackspace Records?';
        return self::askYesOrNo($question);
    }

    protected function getDomainName() {
        if (isset($this->params["domain-name"])) { return ; }
        $question = 'Enter Domain Name';
        $this->params["domain-name"] = self::askForInput($question, true);
    }

    protected function getRecordName() {
        if (isset($this->params["record-name"])) { return ; }
        $question = 'Enter Record Name';
        $this->params["record-name"] = self::askForInput($question, true);
    }

    protected function getDomainEmail() {
        if (isset($this->params["domain-email"])) { return ; }
        $question = 'Enter Domain EMail';
        $this->params["domain-email"] = self::askForInput($question, true);
    }

    protected function getDomainTTL() {
        if (isset($this->params["domain-ttl"])) { return ; }
        $question = 'Enter Domain TTL';
        $this->params["domain-ttl"] = self::askForInput($question, true);
    }

    protected function getRecordType() {
        if (isset($this->params["record-type"])) { return ; }
        $question = 'Enter Record Type';
        $this->params["record-type"] = self::askForInput($question, true);
    }

    protected function getRecordData() {
        if (isset($this->params["record-data"])) { return ; }
        $question = 'Enter Record Data';
        $this->params["record-data"] = self::askForInput($question, true);
    }

    protected function getRecordTTL() {
        if (isset($this->params["record-ttl"])) { return ; }
        $question = 'Enter Record TTL';
        $this->params["record-ttl"] = self::askForInput($question, true);
    }

    protected function getDomainComment() {
        if (isset($this->params["domain-comment"])) { return ; }
        if (isset($this->params["guess"])) {
            $this->params["domain-comment"] = "" ;
            return ; }
        $question = 'Enter an optional Domain Comment';
        $this->params["domain-comment"] = self::askForInput($question, true);
    }

    protected function addDNS($domrec) {
        $provider = $this->getProvider(ucfirst($domrec));
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Adding DNS ".ucfirst($domrec)) ;
        if ($domrec == "record") {
            $result = $provider->addRecord() ; }
        else if ($domrec == "domain") {
            $result = $provider->addDomain() ; }
        return $result ;
    }

    protected function destroyDNS($domrec) {
        $provider = $this->getProvider();
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Removing DNS ".ucfirst($domrec)) ;
        if ($domrec == "record") {
            $result = $provider->deleteRecord() ; }
        else if ($domrec == "domain") {
            $result = $provider->deleteDomain() ; }
        return $result ;
    }

    protected function getProvider($modGroup = "Domain") {
        $infoObjects = \Core\AutoLoader::getInfoObjects();
        $allProviders = array();
        foreach($infoObjects as $infoObject) {
            if ( method_exists($infoObject, "dnsProviderName") ) {
                $allProviders[] = $infoObject->dnsProviderName(); } }
        foreach($allProviders as $oneProvider) {
            if ( (isset($this->providerName) && $this->providerName == $oneProvider) ) {
                $className = '\Model\\'.$oneProvider ;
                $providerFactory = new $className();
                $provider = $providerFactory->getModel($this->params, $modGroup);
                return $provider ; } }
        return false ;
    }

}