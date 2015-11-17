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
            array("method"=> array("object" => $this, "method" => "initialiseEnterprise", "params" => array()) ),
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
    }

    protected function askForAppleXCodeAPIKey(){
        if (isset($this->params["api-key"])) { return $this->params["api-key"] ; }
        $appVar = \Model\AppConfig::getAppVariable("apple-developer-api-key") ;
        if ($appVar != null) {
            $question = 'Use Application saved Apple Developer API Key?';
            if (self::askYesOrNo($question, true) == true) { return $appVar ; } }
        $question = 'Enter Apple Developer API Key';
        return self::askForInput($question, true);
    }

    protected function askForAppleXCodeUsername(){
        if (isset($this->params["username"])) { return $this->params["username"] ; }
        $appVar = \Model\AppConfig::getAppVariable("apple-developer-username") ;
        if ($appVar != null) {
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
        $comm = SUDOPREFIX." xcversion install-cli-tools" ;
        $rc = $this->executeAndGetReturnCode($comm, true, true) ;
        $res = ($rc["rc"] == true) ? true : false ;
        return $res ;
    }

    public function runXCodeInstaller() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Installing XCode Application", $this->getModuleName()) ;
        $comm = SUDOPREFIX." xcversion install" ;
        $rc = $this->executeAndGetReturnCode($comm, true, true) ;
        $res = ($rc["rc"] == true) ? true : false ;
        return $res ;
    }

}