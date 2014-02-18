<?php

Namespace Model;

class HostnameUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $hostnameName ;
    protected $actionsToMethods =
        array( "change" => "performHostnameChange",
               "show" => "performHostnameShow" ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Hostname";
        $this->programDataFolder = "";
        $this->programNameMachine = "hostname"; // command and app dir name
        $this->programNameFriendly = "!Hostname!!"; // 12 chars
        $this->programNameInstaller = "Hostname";
        $this->initialize();
    }

    protected function performHostnameChange() {
        return $this->change();
    }

    protected function performHostnameShow() {
        return $this->getHostname();
    }

    public function change($hostname = null) {
        $consoleFactory = new \Model\Console();
        $console = $consoleFactory->getModel($this->params);
        if (isset($hostname)) { }
        else if (isset($this->params["hostname"])) {
            $hostname = $this->params["hostname"]; }
        else if (isset($autopilot["host-name"])) {
            $hostname = $autopilot["host-name"]; }
        else {
            $hostname = self::askForInput("Enter Hostname:", true); }
        $command = 'sudo dapperstrano he add --host-ip="127.0.0.1" --host-name="'.$hostname.'" --yes' ;
        $returnCode = self::executeAndGetReturnCode($command) ;
        if ($returnCode !== 0) {
            $console->log("Adding host file entry did not execute correctly") ;
            return false ; }
        $return = file_put_contents('/etc/host', $hostname) ;
        if ($return < 1) {
            $console->log("Wrting hostname $hostname to /etc/host failed") ;
            return false ; }
        return true ;
    }

    public function getHostname() {
        $command = 'sudo hostname' ;
        return $this->executeAndLoad($command) ;
    }

}