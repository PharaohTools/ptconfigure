<?php

Namespace Model;

class BoxifyListing extends BaseLinuxApp {

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
        $this->autopilotDefiner = "Boxify";
        $this->programNameMachine = "boxify"; // command and app dir name
        $this->programNameFriendly = "Boxify!"; // 12 chars
        $this->programNameInstaller = "Boxify your Environments";
        $this->initialize();
    }

    public function performListing() {
        if (isset($this->params["env"])) { $this->params["environment-name"] = $this->params["env"]; }
        if (isset($this->params["environment-name"])) { $this->setEnvironment($this->params["environment-name"]); }
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
        $envs = \Model\AppConfig::getProjectVariable("environments");
        if (is_array($envs)) {
//            var_dump("target 2", is_array($envs) ) ;
            foreach ($envs as $env) {
//                var_dump("target 3", $env ) ;
                if ($env["any-app"]["gen_env_name"]=="{$this->params["environment-name"]}") {
//                    var_dump("target 4", $env["servers"][0]["target"] ) ;
//                    $ip_address = $env["servers"][0]["target"] ;
                    $logging->log("Lister has found a matching environment", $this->getModuleName()) ;
                    return $env ; } } }
        $logging->log("Lister could not find a matching environment", $this->getModuleName()) ;
        return false ;
    }

}