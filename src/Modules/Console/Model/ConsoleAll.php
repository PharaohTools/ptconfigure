<?php

Namespace Model;

class ConsoleAll extends BaseLinuxApp {

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