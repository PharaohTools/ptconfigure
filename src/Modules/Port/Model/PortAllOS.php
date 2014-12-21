<?php

Namespace Model;

class PortAllOS extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $ipAddress ;
    protected $iptype ;
    protected $portNumber ;
    protected $actionsToMethods = array(
        "is-responding" => "performPortCheck",
        "process" => "performPortServiceCheck"
    ) ;

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

    protected function performPortServiceCheck() {
        $this->setPort();
        return $this->getPortService();
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

    private function getPortService() {
        // @todo fsockopen takes a while, fixed with 5 sec timeout?
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $comm = 'sudo lsof -i :'.$this->portNumber.' | grep LISTEN';
        $tx = self::executeAndLoad($comm) ;
        $process = substr($tx, 0, strpos($tx, " ")) ;
        $statcomm = 'echo $?';
        $stat = self::executeAndLoad($statcomm) ;
        $stat = str_replace("\n", "", $stat) ;

        if ($stat != "0") {
            \Core\BootStrap::setExitCode(1);
            $logging->log("Port process command execution failed.") ;
            return true; }

        if (isset($tx)) {
            $logging->log("Port {$this->portNumber} is being used by the process {$process}") ;
            if (isset($this->params["expect"])) {
                $logging->log("Expecting process to be {$this->params["expect"]}") ;

                if ($this->params["expect"] != $process ) {
                    $logging->log("Wrong process on Port {$this->portNumber}") ;
                    \Core\BootStrap::setExitCode(1);
                    return false ; } }

            return true; }
        else {
            $logging->log("Port {$this->portNumber} is not being used by a process") ;
            return false; }
    }

}