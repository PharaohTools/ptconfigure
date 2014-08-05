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
    protected $target ;
    protected $interval ;
    protected $maxWait ;
    protected $actionsToMethods = array(
        "once" => "performPingOnce",
        "ten" => "performTenPings",
        "until-responding" => "performPingsUntil",
    ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Ping";
        $this->programDataFolder = "";
        $this->programNameMachine = "ping"; // command and app dir name
        $this->programNameFriendly = "!Ping!!"; // 12 chars
        $this->programNameInstaller = "Ping";
        $this->initialize();
    }

    protected function performPingOnce() {
        require_once ("../Libraries/JJG/Ping.php") ;
        $this->setTarget();
        $this->setInterval();
        $this->doOnePing();
    }

    protected function performTenPings() {
        require_once ("../Libraries/JJG/Ping.php") ;
        $this->setTarget();
        $this->setInterval();
        $this->doTenPings();
    }

    protected function performPingsUntil() {
        require_once ("../Libraries/JJG/Ping.php") ;
        $this->setTarget();
        $this->setInterval();
        $this->setMaxWait();
        $this->doPingsUntil();
    }

    protected function setTarget($target = null) {
        if (isset($target)) {
            $this->target = $target; }
        else if (isset($this->params["target"])) {
            $this->target = $this->params["target"]; }
        else {
            $this->target = self::askForInput("Enter Target: ", true); }
    }

    protected function setInterval() {
        if (isset($this->params["guess"])) {
            $this->interval = "2" ; }
        else if (isset($this->params["interval"])) {
            $this->interval = $this->params["interval"]; }
        else {
            $this->interval = self::askForInput("Enter Interval: ", true); }
    }

    protected function setMaxWait() {
        if (isset($this->params["guess"])) {
            $this->maxWait = "2" ; }
        else if (isset($this->params["max-wait"])) {
            $this->maxWait = $this->params["max-wait"]; }
        else {
            $this->maxWait = self::askForInput("Enter Max Wait: ", true); }
    }

    private function doOnePing() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $ping = new \JJG\Ping($this->target);
        $latency = $ping->ping();
        if ($latency !== false) {
            $logging->log('Ping Latency is ' . $latency . ' ms') ; }
        else {
            $logging->log("Ping Host could not be reached.") ;}
    }

    private function doTenPings() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $ping = new \JJG\Ping($this->target);
        for ($i = 0; $i < 10; $i ++) {
            $latency = $ping->ping();
            if ($latency !== false) {
                $logging->log('Ping Latency is ' . $latency . ' ms') ; }
            else {
                $logging->log("Ping Host could not be reached.") ; }
            sleep($this->interval) ; }
    }

    private function doPingsUntil() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $ping = new \JJG\Ping($this->target);
        $totalTime = 0 ;
        while ($totalTime < $this->maxWait) {
            $latency = $ping->ping();
            if ($latency !== false) {
                $logging->log('Ping Latency is ' . $latency . ' ms') ;
                break ; }
            else {
                $logging->log("Ping Host could not be reached.") ; }
            sleep($this->interval) ;
            $totalTime = $totalTime + $this->interval ; }
    }

}