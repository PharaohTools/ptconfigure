<?php

Namespace Model;

class TaskAllOS extends BaseLinuxApp {

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
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->programNameMachine = "task"; // command and app dir name
        $this->programNameFriendly = "Task!"; // 12 chars
        $this->programNameInstaller = "Task your Environments";
        $this->initialize();
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

}