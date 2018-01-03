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
    public static $logMessage = null ;

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
            self::$logMessage = $this->params["log-message"] ; }
        if (isset($this->params["message"])) {
            self::$logMessage = $this->params["message"] ; }
        else {
            self::$logMessage = self::askForInput("Enter Log Message", true) ; }
    }

    public function log($message = null, $source = null, $log_exit_code = null) {
        if (isset($this->params["log-message"])) {
            self::$logMessage = $this->params["log-message"] ; }
        if (isset($this->params["message"])) {
            self::$logMessage = $this->params["message"] ; }
        if (is_null($source) && isset($this->params["source"])) {
            $source = $this->params["source"] ; }
        if (isset(self::$logMessage)) {
            $message = self::$logMessage ; }

        if (!isset($message) || is_null($message)) {
//            debug_print_backtrace() ;
            return true ; }

        if (!is_null($log_exit_code)) {
            \Core\BootStrap::setExitCode($log_exit_code) ; }
        $stx = (strlen($source)>0) ? "[$source] " : "" ;
        $friendly = substr(PHARAOH_APP, 2) ;
        $friendly = ucfirst($friendly) ;
        $fullMessage = "[Pharaoh {$friendly}] " . $stx . $message . "\n" ;

        $disable_ansi = false ;
        if (!isset($this->params["disable-ansi"]) ||
            (isset($this->params["disable-ansi"]) && $this->params["disable-ansi"] == false)) {
            $disable_ansi = true ;
            $logFactory = new \Model\Logging() ;
            $colours = $logFactory->getModel($this->params, "Colours") ; }

        if (!is_null($log_exit_code)) {
            if ($disable_ansi==false) { $fullMessage = $colours->getColoredString($fullMessage, "red", null) ; }
            $res = file_put_contents("php://stderr", $fullMessage ); }
        else {
            if ($disable_ansi==false) { $fullMessage = $colours->getColoredString($fullMessage, "green", null) ; }
            $res = file_put_contents("php://stdout", $fullMessage ); }

        if ($res==false) { return false ;}
        if (isset($this->params["php-log"])) {
            $res = error_log($fullMessage) ;
            if ($res==false) { return false ;} }
        self::$logMessage = null ;
        return true ;
    }

}