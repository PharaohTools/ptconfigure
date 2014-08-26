<?php

Namespace Model;

class PortUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $ipAddress ;
    protected $portNumber ;
    protected $actionsToMethods = array( "is-responding" => "performPortCheck" ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Port";
        $this->programDataFolder = "";
        $this->programNameMachine = "port"; // command and app dir name
        $this->programNameFriendly = "!Port!!"; // 12 chars
        $this->programNameInstaller = "Port";
        $this->initialize();
    }

    protected function performPortCheck() {
        $this->setIp();
        $this->setPort();
        return $this->getPortStatus();
    }

    public function setIp($ipAddress = null) {
        if (isset($ipAddress)) {
            $this->ipAddress = $ipAddress; }
        else if (isset($this->params["ip-address"])) {
            $this->ipAddress = $this->params["ip-address"]; }
        else if (isset($this->params["ip"])) {
            $this->ipAddress = $this->params["ip"]; }
        else {
            $this->ipAddress = self::askForInput("Enter IP Adress:", true); }
    }

    public function setPort($portNumber = null) {
        if (isset($portNumber)) {
            $this->portNumber = $portNumber; }
        else if (isset($this->params["port-number"])) {
            $this->portNumber = $this->params["port-number"]; }
        else if (isset($this->params["port"])) {
            $this->portNumber = $this->params["port"]; }
        else {
            $this->portNumber = self::askForInput("Enter Port Number:", true); }
    }

    private function getPortStatus() {
        // @todo fsockopen takes a while, fixed with 5 sec timeout?
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $result = @fsockopen($this->ipAddress, $this->portNumber, $errno, $errstr, 5);
        if (is_resource($result)) {
            $logging->log("Port {$this->portNumber} is responding") ;
            return true; }
        else {
            $logging->log("Port {$this->portNumber} is not responding. Error: $errno, $errstr") ;
            return false; }
    }

}