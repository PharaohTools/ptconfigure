<?php

Namespace Model;

class SaharaWindows extends BaseLinuxApp {

    // Compatibility
    public $os = array("Windows", "WINNT") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $hostnameName ;
    protected $actionsToMethods =
        array( "change" => "performSaharaChange",
               "show" => "performSaharaShow" ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Sahara";
        $this->programDataFolder = "";
        $this->programNameMachine = "hostname"; // command and app dir name
        $this->programNameFriendly = "!Sahara!!"; // 12 chars
        $this->programNameInstaller = "Sahara";
        $this->initialize();
    }

    protected function performSaharaChange() {
        return $this->change();
    }

    protected function performSaharaShow() {
        return $this->getSahara();
    }

    public function change($hostname = null) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (isset($hostname)) { }
        else if (isset($this->params["hostname"])) {
            $hostname = $this->params["hostname"]; }
        else {
            $hostname = self::askForInput("Enter Sahara:", true); }
        $command = SUDOPREFIX.'ptdeploy he add --host-ip="127.0.0.1" --host-name="'.$hostname.'" --yes' ;
        $returnCode = self::executeAndGetReturnCode($command) ;
        if ($returnCode !== 0) {
            $logging->log("Adding host file entry did not execute correctly") ;
            return false ; }
        $return = file_put_contents('/etc/host', $hostname) ;
        if ($return < 1) {
            $logging->log("Writing hostname $hostname to /etc/host failed") ;
            return false ; }
        return true ;
    }

    public function getSahara() {
        $command = 'hostname' ;
        return $this->executeAndLoad($command) ;
    }

}