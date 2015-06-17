<?php

Namespace Model;

class MongoDBUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
//        $newRootPass = $this->getNewRootPass();
        $this->autopilotDefiner = "MongoDB";
        $this->installCommands = array(
//            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "debconf-utils")) ),
//            array("command"=> array(
//                    "echo mongodb-server mongodb-server/root_password password $newRootPass | ".SUDOPREFIX." debconf-set-selections",
//                    "echo mongodb-server mongodb-server/root_password_again password $newRootPass | ".SUDOPREFIX." debconf-set-selections" ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "mongodb-client")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "mongodb-server")) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "mongodb-client")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "mongodb-server")) ),
//            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "debconf-utils")) ),
        );
        $this->programDataFolder = "/opt/MongoDB"; // command and app dir name
        $this->programNameMachine = "mongodbserver"; // command and app dir name
        $this->programNameFriendly = "MongoDB Server!"; // 12 chars
        $this->programNameInstaller = "MongoDB Server";
        $this->statusCommand = "mongod --version" ;
        $this->versionInstalledCommand = SUDOPREFIX."apt-cache policy mongodb" ;
        $this->versionRecommendedCommand = SUDOPREFIX."apt-cache policy mongodb" ;
        $this->versionLatestCommand = SUDOPREFIX."apt-cache policy mongodb" ;
        $this->initialize();
    }

    private function getNewRootPass() {
        if (isset($this->params["mongodb-root-pass"])) {
            $newRootPass = $this->params["mongodb-root-pass"] ; }
        else if (AppConfig::getProjectVariable("mongodb-default-root-pass") != "") {
            $newRootPass = AppConfig::getProjectVariable("mongodb-default-root-pass") ; }
        else {
            $newRootPass = "ptconfigure" ; }
        return $newRootPass;
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 27, 23) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        if ($this->askStatus() == true) { $done = substr($text, 64, 23) ; }
        else { $done = substr($text, 47, 23) ;}
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        if ($this->askStatus() == true) { $done = substr($text, 64, 23) ; }
        else { $done = substr($text, 47, 23) ;}
        return $done ;
    }

}
