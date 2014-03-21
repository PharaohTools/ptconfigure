<?php

Namespace Model;

class ApacheServerUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "ApacheServer";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAddDeps", "params" => array("apache2")) ),
            array("method"=> array("object" => $this, "method" => "apacheRestart", "params" => array())) );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemoveDeps", "params" => array("apache2")) ),
            array("method"=> array("object" => $this, "method" => "apacheRestart", "params" => array())) );
        $this->programDataFolder = "/opt/ApacheServer"; // command and app dir name
        $this->programNameMachine = "apacheserver"; // command and app dir name
        $this->programNameFriendly = "Apache Server!"; // 12 chars
        $this->programNameInstaller = "Apache Server";
        $this->statusCommand = "apache2 -v" ;
        $this->initialize();
    }

    public function packageAddDeps($package) {
        $packageFactory = new PackageManager();
        $packageManager = $packageFactory->getModel($this->params) ;
        $packageManager->performPackageEnsure("Apt", $package, $this);
    }

    public function packageRemoveDeps($package) {
        $packageFactory = new PackageManager();
        $packageManager = $packageFactory->getModel($this->params) ;
        $packageManager->performPackageRemove("Apt", $package, $this);
    }

    public function apacheRestart() {
        $serviceFactory = new Service();
        $serviceManager = $serviceFactory->getModel($this->params) ;
        $serviceManager->setService("apache2");
        $serviceManager->restart();
    }

}