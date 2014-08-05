<?php

Namespace Model;

class PingUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $portNumber ;
    protected $actionsToMethods = array( "is-responding" => "performPingCheck" ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Ping";
        $this->programDataFolder = "";
        $this->programNameMachine = "port"; // command and app dir name
        $this->programNameFriendly = "!Ping!!"; // 12 chars
        $this->programNameInstaller = "Ping";
        $this->initialize();
    }

    protected function performPingCheck() {
        $this->setPing();
        return $this->getPingStatus();
    }

    public function setPing($portNumber = null) {
        if (isset($portNumber)) {
            $this->portNumber = $portNumber; }
        else if (isset($this->params["port-number"])) {
            $this->portNumber = $this->params["port-number"]; }
        else if (isset($this->params["port"])) {
            $this->portNumber = $this->params["port"]; }
        else {
            $this->portNumber = self::askForInput("Enter Ping Number:", true); }
    }

    private function getPingStatus() {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) { throw new Exception("Cannot create socket"); }
        $result = @socket_connect($socket, '127.0.0.1', $this->number);
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ($result !== false) {
            $logging->log("Ping {$this->portNumber} is responding") ;
            return true; }
        else {
            $logging->log("Ping {$this->portNumber} is not responding") ;
            return false;}
    }

}