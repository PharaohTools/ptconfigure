<?php

Namespace Model;

class SaharaUbuntu extends BaseLinuxApp {

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
        array( "mode-on" => "performSaharaModeOn",
               "mode-off" => "performSaharaModeOff" ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Sahara";
        $this->programDataFolder = "";
        $this->programNameMachine = "sahara"; // command and app dir name
        $this->programNameFriendly = "!Sahara!!"; // 12 chars
        $this->programNameInstaller = "Sahara";
        $this->initialize();
    }

    public function performSaharaModeOn($provider = null, $sahara_server = null) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (isset($provider)) { }
        else if (isset($this->params["provider"])) {
            $provider = $this->params["provider"]; }
        else {
            $provider = self::askForInput("Enter Provider:", true); }
        if (isset($sahara_server)) { }
        else if (isset($this->params["sahara"])) {
            $sahara_server = $this->params["sahara"]; }
        else {
            $sahara_server = self::askForInput("Enter Sahara Server:", true); }
        $hostname = $this->getHostnameFromProvider($provider) ;
        if (is_null($hostname)) {
            $logging->log("Unable to find hostname from Provider $provider") ;
            return false ;
        }
        $command = SUDOPREFIX.'ptdeploy he add --host-ip="'.$sahara_server.'" --host-name="'.$hostname.'" --yes' ;
        $returnCode = self::executeAndGetReturnCode($command) ;
        if ($returnCode['rc'] !== 0) {
            $logging->log("Adding host file entry did not execute correctly") ;
            return false ; }
        $logging->log("Sahara Override Mode Switched on for Provider: $provider, using Sahara API: $sahara_server") ;
        return true ;
    }

    public function performSaharaModeOff($provider = null, $sahara_server = null) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (isset($provider)) { }
        else if (isset($this->params["provider"])) {
            $provider = $this->params["provider"]; }
        else {
            $provider = self::askForInput("Enter Provider:", true); }
        $hostname = $this->getHostnameFromProvider($provider) ;
        if (is_null($hostname)) {
            $logging->log("Unable to find hostname from Provider $provider") ;
            return false ;
        }
        $command = SUDOPREFIX.'ptdeploy he rm --host-name="'.$hostname.'" -yg' ;
        $returnCode = self::executeAndGetReturnCode($command) ;
        if ($returnCode['rc'] !== 0) {
            $logging->log("Removing host file entry did not execute correctly") ;
            return false ; }
        $logging->log("Sahara Override Mode Switched Off for Provider: $provider") ;
        return true ;
    }

    public function getHostnameFromProvider($provider) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $digital_ocean = ['do', 'digitalocean', 'digtal-ocean'] ;
        if (in_array($provider, $digital_ocean)) {
            $hostname = 'api.digitalocean.com' ;
            $logging->log("Found Hostname $hostname") ;
            return $hostname ;
        }
        $aws = ['aws', 'amazon'] ;
        if (in_array($provider, $aws)) {
            $hostname = 'route53.amazonaws.com' ;
            $logging->log("Found Hostname $hostname") ;
            return $hostname ;
        }
        $logging->log("No Hostname available for provider") ;
        return null ;
    }

}