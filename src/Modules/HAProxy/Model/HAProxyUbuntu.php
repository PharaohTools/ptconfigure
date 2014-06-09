<?php

Namespace Model;

class HAProxyUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "HAProxy";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "haproxy")) ),
            array("method"=> array("object" => $this, "method" => "addInitScript", "params" => array())),
            array("method"=> array("object" => $this, "method" => "haproxyRestart", "params" => array()))
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "haproxy")) ),
            array("method"=> array("object" => $this, "method" => "delInitScript", "params" => array())),
            array("method"=> array("object" => $this, "method" => "haproxyRestart", "params" => array())) );
        $this->programDataFolder = "/opt/HAProxy"; // command and app dir name
        $this->programNameMachine = "haproxy"; // command and app dir name
        $this->programNameFriendly = "HA Proxy Server!"; // 12 chars
        $this->programNameInstaller = "HA Proxy Server";
        $this->statusCommand = "sudo haproxy -v" ;
        $this->versionInstalledCommand = "sudo apt-cache policy haproxy" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy haproxy" ;
        $this->versionLatestCommand = "sudo apt-cache policy haproxy" ;
        $this->initialize();
    }

    public function addInitScript() {
        $templatesDir = str_replace("Model", "Templates", dirname(__FILE__) ) ;
        $templateSource = $templatesDir.'/haproxy';
        $templatorFactory = new \Model\Templating();
        $templator = $templatorFactory->getModel($this->params);
        $newFileName = "/etc/default/haproxy" ;
        $templator->template(
            file_get_contents($templateSource),
            array(),
            $newFileName );
        echo "HA Proxy Init script config file $newFileName added\n";
    }

    public function delInitScript() {
        unlink("/etc/default/haproxy");
        echo "HA Proxy Init script config file /etc/default/haproxy removed\n";
    }

    public function haproxyRestart() {
        $serviceFactory = new Service();
        $serviceManager = $serviceFactory->getModel($this->params) ;
        $serviceManager->setService("haproxy");
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