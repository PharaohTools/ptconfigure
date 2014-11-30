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