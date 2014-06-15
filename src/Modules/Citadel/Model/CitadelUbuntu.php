<?php

Namespace Model;

class CitadelUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("12.04", "12.10") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Citadel";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "citadel-server")) ),
            // array("method"=> array("object" => $this, "method" => "addInitScript", "params" => array())),
            array("method"=> array("object" => $this, "method" => "citadelRestart", "params" => array()))
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "citadel-server")) ),
            // array("method"=> array("object" => $this, "method" => "delInitScript", "params" => array())),
            array("method"=> array("object" => $this, "method" => "citadelRestart", "params" => array())) );
        $this->programDataFolder = "/opt/Citadel"; // command and app dir name
        $this->programNameMachine = "citadel"; // command and app dir name
        $this->programNameFriendly = "Citadel Server!"; // 12 chars
        $this->programNameInstaller = "Citadel Server";
        $this->statusCommand = "sudo citadel -v" ;
        $this->versionInstalledCommand = "sudo apt-cache policy citadel" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy citadel" ;
        $this->versionLatestCommand = "sudo apt-cache policy citadel" ;
        $this->initialize();
    }

    public function addInitScript() {
        $templatesDir = str_replace("Model", "Templates", dirname(__FILE__) ) ;
        $templateSource = $templatesDir.'/citadel';
        $templatorFactory = new \Model\Templating();
        $templator = $templatorFactory->getModel($this->params);
        $newFileName = "/etc/default/citadel" ;
        $templator->template(
            file_get_contents($templateSource),
            array(),
            $newFileName );
        echo "Citadel Init script config file $newFileName added\n";
    }

    public function delInitScript() {
        unlink("/etc/default/citadel");
        echo "Citadel Init script config file /etc/default/citadel removed\n";
    }

    public function citadelRestart() {
        $serviceFactory = new Service();
        $serviceManager = $serviceFactory->getModel($this->params) ;
        $serviceManager->setService("citadel");
        $serviceManager->restart();
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 22, 8) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 44, 8) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 44, 8) ;
        return $done ;
    }

}