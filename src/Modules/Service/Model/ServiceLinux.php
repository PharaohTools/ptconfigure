<?php

Namespace Model;

class ServiceLinux extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $serviceName ;
    protected $actionsToMethods =
        array(
            "start" => "performServiceStart",
            "stop" => "performServiceStop",
            "restart" => "performServiceRestart",
            "is-running" => "performServiceIsRunningCheck",
            "ensure-running" => "performServiceEnsureRunning",
            "run-at-reboots" => "performServiceRunAtReboots"
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Service";
        $this->programDataFolder = "";
        $this->programNameMachine = "service"; // command and app dir name
        $this->programNameFriendly = "!Service!!"; // 12 chars
        $this->programNameInstaller = "Service";
        $this->initialize();
    }

    protected function performServiceStart() {
        $this->setService();
        return $this->start();
    }

    protected function performServiceStop() {
        $this->setService();
        return $this->stop();
    }

    protected function performServiceRestart() {
        $this->setService();
        return $this->restart();
    }

    protected function performServiceEnsureRunning() {
        $this->setService();
        return $this->ensureRunning();
    }

    protected function performServiceIsRunningCheck() {
        $this->setService();
        return $this->isRunning();
    }

    protected function performServiceRunAtReboots() {
        $this->setService();
        return $this->runAtReboots();
    }

    public function setService($serviceName = null) {
        if (isset($serviceName)) {
            $this->serviceName = $serviceName; }
        else if (isset($this->params["servicename"])) {
            $this->serviceName = $this->params["servicename"]; }
        else if (isset($this->params["service-name"])) {
            $this->serviceName = $this->params["service-name"]; }
        else if (isset($this->params["servicename"])) {
            $this->serviceName = $this->params["servicename"]; }
        else if (isset($this->params["service-name"])) {
            $this->serviceName = $this->params["service-name"]; }
        else {
            $this->serviceName = self::askForInput("Enter Service Name:", true); }
    }


    /* brought in */
    /*@todo */

    public function ensureRunning() {
        $status = $this->executeAndLoad("service {$this->serviceName} status 2> /dev/null");
        if(strpos($status, 'running') != false) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Service {$this->serviceName} is running...") ; }
        else {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Service {$this->serviceName} is not running...") ;
            $logging->log("Starting {$this->serviceName} service") ;
            $this->executeAndOutput("service {$this->serviceName} start"); }
        return true ;
    }

    public function isRunning() {
        $status = $this->executeAndGetReturnCode("service {$this->serviceName} status");
        if($status == 0) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Service {$this->serviceName} is running...") ;
            return true ; }
        else {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            \Core\BootStrap::setExitCode(1);
            $logging->log("Service {$this->serviceName} is not running...") ;
            return false ; }
    }

    public function runAtReboots() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Removing current {$this->serviceName} service startup links") ;
        $this->executeAndOutput("sudo update-rc.d -f {$this->serviceName} remove");
        $logging->log("Adding {$this->serviceName} service startup links") ;
        $this->executeAndOutput("sudo update-rc.d {$this->serviceName} defaults");
        return true ;
    }

    public function start() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Starting {$this->serviceName} service") ;
        $this->executeAndOutput("service {$this->serviceName} start");
        return true ;
    }

    public function stop() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Stopping {$this->serviceName} service") ;
        $this->executeAndOutput("service {$this->serviceName} stop");
        return true ;
    }

    public function restart() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Restarting {$this->serviceName} service") ;
        $this->executeAndOutput("service {$this->serviceName} restart");
        return true ;
    }

}