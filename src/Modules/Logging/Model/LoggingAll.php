<?php

Namespace Model;

class LoggingAll extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    // Model Group
    private $logMessage = null ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Logging";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "setLogMessage", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "log", "params" => array()) ),);
        $this->uninstallCommands = array();
        $this->programDataFolder = "/opt/Logging"; // command and app dir name
        $this->programNameMachine = "logging"; // command and app dir name
        $this->programNameFriendly = "  Logging!  "; // 12 chars
        $this->programNameInstaller = "Logging";
        $this->initialize();
    }

    public function setLogMessage() {
        if (isset($this->params["log-message"])) {
            $this->logMessage = $this->params["log-message"] ; }
        else {
            $this->logMessage = self::askForInput("Enter Log Message", true) ; }
    }

    public function log($message = null) {
        if (isset($this->logMessage)) { $message = $this->logMessage ; }
        $fullMessage = "[Pharoah Logging] " . $message . "\n" ;
        file_put_contents("php://stderr", $fullMessage );
        if (isset($this->params["php-log"])) {
            error_log($fullMessage) ; }
    }

}