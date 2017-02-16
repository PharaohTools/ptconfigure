<?php

Namespace Model;

class ApacheServerUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("12.04", "12.10", "13.04", "13.10", "14.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public $packageName = "apache2" ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "ApacheServer";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "apache2")) ),
            array("method"=> array("object" => $this, "method" => "apacheRestart", "params" => array())) );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "apache2")) ),
            array("method"=> array("object" => $this, "method" => "apacheRestart", "params" => array())) );
        $this->programDataFolder = "/opt/ApacheServer"; // command and app dir name
        $this->programNameMachine = "apacheserver"; // command and app dir name
        $this->programNameFriendly = "Apache Server!"; // 12 chars
        $this->programNameInstaller = "Apache Server";
        $this->statusCommand = SUDOPREFIX."which apache2" ;
        $this->versionInstalledCommand = SUDOPREFIX."apache2 -v" ;
        $this->versionRecommendedCommand = SUDOPREFIX."apt-cache policy apache2" ;
        $this->versionLatestCommand = SUDOPREFIX."apt-cache policy apache2" ;
        $this->serviceCommand = "apache2" ;
        $this->rebootsCommand = "apache2" ;
        $this->initialize();
    }

    public function apacheRestart() {
        $serviceFactory = new \Model\Service();
        $serviceManager = $serviceFactory->getModel($this->params) ;
        $serviceManager->setService("apache2");
        $serviceManager->restart();
    }

    public function versionInstalledCommandTrimmer($text) {
        $rest = substr($text, 23) ;
        $spacepos = strpos($rest, " ") ;
        $done =  substr($rest, 0, $spacepos) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        if (strpos($text, "Installed: (none)") !== false) { $rest = substr($text, 42) ; }
        else {  $rest = substr($text, 52) ; }
        $spacepos = strpos($rest, "\n") ;
        $done =  substr($rest, 0, $spacepos) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 53, 17) ;
        return $done ;
    }

}