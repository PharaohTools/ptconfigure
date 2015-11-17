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

    public function __construct($params) {
        parent::__construct($params);
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Gem", "xcode-install" ) ) ),
            array("method"=> array("object" => $this, "method" => "xcodeCliToolsInstall", "params" => array()) ),
        );
        $this->uninstallCommands = array( );
        $this->programDataFolder = "/opt/XCode"; // command and app dir name
        $this->programNameMachine = "xcode"; // command and app dir name
        $this->programNameFriendly = "XCode on OSx"; // 12 chars
        $this->programNameInstaller = "XCode for OSx";
        $this->statusCommand = "xcversion list" ;
        $this->initialize();
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