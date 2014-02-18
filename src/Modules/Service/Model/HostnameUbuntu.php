<?php

Namespace Model;

class ServiceUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $serviceName ;
    protected $actionsToMethods =
        array( "change" => "performServiceChange",
               "show" => "performServiceShow" ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Service";
        $this->programDataFolder = "";
        $this->programNameMachine = "service"; // command and app dir name
        $this->programNameFriendly = "!Service!!"; // 12 chars
        $this->programNameInstaller = "Service";
        $this->initialize();
    }

    protected function performServiceChange() {
        return $this->change();
    }

    protected function performServiceShow() {
        return $this->getService();
    }

    public function change($service = null) {
        $consoleFactory = new \Model\Console();
        $console = $consoleFactory->getModel($this->params);
        if (isset($service)) { }
        else if (isset($this->params["service"])) {
            $service = $this->params["service"]; }
        else if (isset($autopilot["host-name"])) {
            $service = $autopilot["host-name"]; }
        else {
            $service = self::askForInput("Enter Service:", true); }
        $command = 'sudo dapperstrano he add --host-ip="127.0.0.1" --host-name="'.$service.'" --yes' ;
        $returnCode = self::executeAndGetReturnCode($command) ;
        if ($returnCode !== 0) {
            $console->log("Adding host file entry did not execute correctly") ;
            return false ; }
        $return = file_put_contents('/etc/host', $service) ;
        if ($return < 1) {
            $console->log("Wrting service $service to /etc/host failed") ;
            return false ; }
        return true ;
    }

    public function getService() {
        $command = 'sudo service' ;
        return $this->executeAndLoad($command) ;
    }

}