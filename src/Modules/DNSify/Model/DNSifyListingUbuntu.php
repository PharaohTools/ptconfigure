<?php

Namespace Model;

class DNSifyListingUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Listing") ;
    protected $environmentName ;
    protected $providerName ;
    protected $boxAmount ;
    protected $requestingModule ;
    protected $actionsToMethods =
        array(
            "list-papyrus" => "performListing",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "DNSify";
        $this->programNameMachine = "boxify"; // command and app dir name
        $this->programNameFriendly = "DNSify!"; // 12 chars
        $this->programNameInstaller = "DNSify your Environments";
        $this->initialize();
    }

    public function performListing() {
        if (isset($this->params["environment-name"])) {
            $this->setEnvironment($this->params["environment-name"]); }
        return $this->listBoxes();
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

    protected function listBoxes() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $returns = array() ;
        $envs = \Model\AppConfig::getProjectVariable("environments");
        return (is_null($envs)) ? null : $envs ;
    }

}