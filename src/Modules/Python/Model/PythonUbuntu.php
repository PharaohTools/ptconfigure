<?php

Namespace Model;

class PythonUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "Python";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", array("python", "python-docutils"))) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", array("python", "python-docutils"))) ),
        );
        $this->programDataFolder = "";
        $this->programNameMachine = "python"; // command and app dir name
        $this->programNameFriendly = "!Python!!"; // 12 chars
        $this->programNameInstaller = "Python";
        $this->versionInstalledCommand = "python --version 2>&1" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy python" ;
        $this->versionLatestCommand = "sudo apt-cache policy python" ;
        $this->initialize();
    }
    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 7, strlen($text)-8) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 53, 17) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 53, 17) ;
        return $done ;
    }

}