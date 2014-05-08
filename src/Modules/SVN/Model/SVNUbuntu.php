<?php

Namespace Model;

class SVNUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "SVN";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "subversion")) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "subversion")) ),
        );
        $this->programDataFolder = "";
        $this->programNameMachine = "svn"; // command and app dir name
        $this->programNameFriendly = "!Subversion!"; // 12 chars
        $this->programNameInstaller = "SVN";
        $this->statusCommand = "svn --version";
        $this->versionInstalledCommand = "sudo apt-cache policy subversion" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy subversion" ;
        $this->versionLatestCommand = "sudo apt-cache policy subversion" ;
        $this->initialize();
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 25, 21) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 60, 21) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 60, 21) ;
        return $done ;
    }

}