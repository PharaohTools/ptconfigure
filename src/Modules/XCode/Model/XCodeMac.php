<?php

Namespace Model;

class XCodeMac extends BaseLinuxApp {

    // Compatibility
    public $os = array("Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    protected $username ;
    protected $pass ;

    public function __construct($params) {
        parent::__construct($params);
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "xEcho", "params" => array())),
            array("method"=> array("object" => $this, "method" => "initialiseXCode", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Gem", "xcode-install" ))),
            array("method"=> array("object" => $this, "method" => "xcodeCliToolsInstall", "params" => array())),
            array("method"=> array("object" => $this, "method" => "runXCodeInstaller", "params" => array()))
        );
        $this->uninstallCommands = array( );
        $this->programDataFolder = "/opt/XCode"; // command and app dir name
        $this->programNameMachine = "xcode"; // command and app dir name
        $this->programNameFriendly = "XCode on OSx"; // 12 chars
        $this->programNameInstaller = "XCode for OSx";
        $this->statusCommand = "xcversion list" ;
        $this->initialize();
    }


    public function initialiseXCode() {
        $this->username = $this->askForAppleXCodeUsername();
        $this->apiKey = $this->askForAppleXCodeAPIKey();
        if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Storing Apple Developer credentials...", $this->getModuleName()) ;
            \Model\AppConfig::setAppVariable("apple-developer-username", $this->username);
            \Model\AppConfig::setAppVariable("apple-developer-pass", $this->pass) ; }
        return true ;
    }

    protected function askForAppleXCodeAPIKey(){
        if (isset($this->params["pass"])) { return $this->params["pass"] ; }
        $appVar = \Model\AppConfig::getAppVariable("apple-developer-pass") ;
        if ($appVar != null) {
            if (isset($appVar) && $this->params["yes"]==true) { return $appVar ; }
            $question = 'Use Application saved Apple Developer Password?';
            if (self::askYesOrNo($question, true) == true) { return $appVar ; } }
        $question = 'Enter Apple Developer Password';
        return self::askForInput($question, true);
    }

    protected function askForAppleXCodeUsername(){
        if (isset($this->params["username"])) { return $this->params["username"] ; }
        $appVar = \Model\AppConfig::getAppVariable("apple-developer-username") ;
        if ($appVar != null) {
            if (isset($appVar) && $this->params["yes"]==true) { return $appVar ; }
            $question = 'Use Application saved Apple Developer User Name?';
            if (self::askYesOrNo($question, true) == true) {
                return $appVar ; } }
        $question = 'Enter Apple Developer User Name';
        return self::askForInput($question, true);
    }

    public function xEcho() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Echoing something", $this->getModuleName()) ;
        return true ;
    }

    public function xcodeCliToolsInstall() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Installing XCode CLI Tools", $this->getModuleName()) ;
        $comm  = 'XCODE_INSTALL_USER="'.$this->username.'" '."\n" ;
        $comm .= 'XCODE_INSTALL_PASSWORD="'.$this->pass.'" '."\n" ;
        $comm .= SUDOPREFIX." xcversion install" ;
        $rc = $this->executeAndGetReturnCode($comm, true, true) ;
        $res = ($rc["rc"] == 0) ? true : false ;
        return $res ;
    }

    public function runXCodeInstaller() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Installing XCode Application", $this->getModuleName()) ;
//        $comm  = 'XCODE_INSTALL_USER="'.$this->username.'" '."\n" ;
//        $comm .= 'XCODE_INSTALL_PASSWORD="'.$this->pass.'" '."\n" ;
//        $comm .= 'FASTLANE_USER="'.$this->username.'" '."\n" ;
//        $comm .= 'FASTLANE_PASS="'.$this->pass.'" '."\n" ;
        $comm  = 'XCODE_INSTALL_USER="'.$this->username.'" '."\n" ;
        $comm .= 'XCODE_INSTALL_PASSWORD="'.$this->pass.'" '."\n" ;
//        $comm .= 'export FASTLANE_USER'."\n" ;
//        $comm .= 'export FASTLANE_PASS'."\n" ;
        $comm .= "xcversion install" ;
        passthru($comm, $rc) ;
        $res = ($rc == 0) ? true : false ;
        return $res ;
    }

}