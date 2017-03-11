<?php

Namespace Model;

class NodeJSUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array(array("11.04", "+")) ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "NodeJS";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", array("npm", "nodejs", "nodejs-legacy"))) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", array("npm", "nodejs", "nodejs-legacy"))) ),
        );
        $this->programDataFolder = "/opt/NodeJS"; // command and app dir name
        $this->programNameMachine = "nodejs"; // command and app dir name
        $this->programNameFriendly = "Node JS!"; // 12 chars
        $this->programNameInstaller = "Node JS";
        $this->statusCommand = SUDOPREFIX."node -v" ;
        $this->versionInstalledCommand = SUDOPREFIX."apt-cache policy nodejs" ;
        $this->versionRecommendedCommand = SUDOPREFIX."apt-cache policy nodejs" ;
        $this->versionLatestCommand = SUDOPREFIX."apt-cache policy nodejs" ;
        $this->initialize();
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 21, 21) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 56, 21) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 56, 21) ;
        return $done ;
    }

}