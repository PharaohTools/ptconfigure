<?php

Namespace Model;

class ConsoleUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("12.04", "12.10") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    // Model Group
    private $logMessage = null ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Console";
        $this->installCommands = array();
        $this->uninstallCommands = array();
        $this->programDataFolder = "/opt/Console"; // command and app dir name
        $this->programNameMachine = "console"; // command and app dir name
        $this->programNameFriendly = "  Console!  "; // 12 chars
        $this->programNameInstaller = "Console";
        $this->registeredPostInstallFunctions = array("setLogMessage", "log");
        $this->initialize();
    }

    public function setLogMessage() {
        if (isset($this->params["console-log-message"])) {
            $this->logMessage = $this->params["console-log-message"] ; }
        else {
            $this->logMessage = self::askForInput("Enter Log Message", true) ; }
    }

    public function log($message = null) {
        if (isset($this->logMessage)) { $message = $this->logMessage ; }
        file_put_contents("php://stderr", "[Pharoah Console] " . $message . "\n");
    }

}