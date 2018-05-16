<?php

Namespace Model;

class PortDebian extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $ipAddress ;
    protected $iptype ;
    protected $portNumber ;
    protected $maxWait ;
    protected $interval ;
    protected $actionsToMethods = array(
        "until-responding" => "checkPortUntil",
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
        else if (isset($this->params["host"])) {
            $this->ipAddress = $this->params["host"]; }
        else if (isset($this->params["hostname"])) {
            $this->ipAddress = $this->params["hostname"]; }
        else if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $this->ipAddress = '127.0.0.1'; }
        else {
            $this->ipAddress = self::askForInput("Enter IP Address:", true); }
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

    protected function setInterval() {
        if (isset($this->params["interval"])) {
            $this->interval = $this->params["interval"]; }
        else if (isset($this->params["guess"])) {
            $this->interval = "2" ; }
        else {
            $this->interval = self::askForInput("Enter Interval: ", true); }
    }

    protected function setMaxWait() {
        if (isset($this->params["max-wait"])) {
            $this->maxWait = $this->params["max-wait"]; }
        else if (isset($this->params["guess"])) {
            $this->maxWait = "60" ; }
        else {
            $this->maxWait = self::askForInput("Enter Max Wait Time: ", true); }
    }

    protected function getPortStatus() {
        // @todo fsockopen takes a while, fixed with 5 sec timeout?
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $result = @fsockopen($this->ipAddress, $this->portNumber, $errno, $errstr, 5);
        if (is_resource($result)) {
            $logging->log("Port {$this->portNumber} is responding", $this->getModuleName()) ;
            return true; }
        else {
            $logging->log("Port {$this->portNumber} is not responding. Error: $errno, $errstr", $this->getModuleName()) ;
            return false; }
    }

    protected function checkPortUntil() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params) ;
        $this->setInterval();
        $this->setMaxWait();
        $totalTime = 0 ;
        $i = 0;
        while ($totalTime < $this->maxWait) {
            $port_result = $this->performPortCheck() ;
            if ($port_result == true) {
                $logging->log("Port {$this->portNumber} is responding after {$totalTime} seconds", $this->getModuleName()) ;
                return true ;
            }
            sleep($this->interval) ;
            $totalTime = $totalTime + $this->interval ;
            $i++; }
        return false ;
    }

    protected function getPortService() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ($this->installDependencies() == false) { return false ;}
        $comm = SUDOPREFIX.'lsof -i :'.$this->portNumber.' | grep LISTEN ';
        $out = self::executeAndGetReturnCode($comm, false, true) ;
        if ( ($out["rc"] == "1" && $out["output"] === array())) {
            \Core\BootStrap::setExitCode(1);
            $logging->log("Port {$this->portNumber} is not being used by a process", $this->getModuleName()) ;
            return false; }
        $ox =  self::executeAndLoad($comm) ;
//        var_dump($out) ;
        $out["output"][0] = $ox ;
        $process = substr($ox, 0, strpos($ox, " ")) ;

        $comm2 = SUDOPREFIX.'lsof -ti :'.$this->portNumber.' | grep LISTEN ';
        $ox2 =  self::executeAndLoad($comm2) ;

        $proc_tails = explode(" ", $out["output"][0]) ;;
        $proc_tails = array_diff($proc_tails, array("")) ;
        $proc_tails = array_values($proc_tails) ;

        var_dump('oxy', $proc_tails[5]) ;
        $process_id = $proc_tails[5] ;

        if (isset($process)) {
            $logging->log("Port {$this->portNumber} is being used by the process {$process}", $this->getModuleName()) ;
            if (isset($this->params["expect"])) {
                $logging->log("Expecting process to be {$this->params["expect"]}", $this->getModuleName()) ;
                if ($this->params["expect"] != $process ) {
                    $logging->log("Unexpected process '{$process}' using Port {$this->portNumber}", $this->getModuleName()) ;
                    \Core\BootStrap::setExitCode(1);
                    return false ; } }
            $logging->log("Process was found with id: {$process_id}", $this->getModuleName()) ;
            return $process_id ; }
        return false ;
    }

    protected function installDependencies() {
        return true ;
    }

}