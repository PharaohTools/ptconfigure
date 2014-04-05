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
        $this->setPort();
        return $this->getPortStatus();
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
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) { throw new Exception("Cannot create socket"); }
        $result = @socket_connect($socket, '127.0.0.1', $this->number);
        $consoleFactory = new \Model\Console();
        $console = $consoleFactory->getModel($this->params);
        if ($result !== false) {
            $console->log("Port {$this->portNumber} is responding") ;
            return true; }
        else {
            $console->log("Port {$this->portNumber} is not responding") ;
            return false;}
    }

}