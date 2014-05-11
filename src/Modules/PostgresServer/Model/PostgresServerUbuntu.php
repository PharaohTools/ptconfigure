<?php

Namespace Model;

class PostgresServerUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "PostgresServer";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", array("postgresql"))) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", array("postgresql-contrib"))) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", array("postgresql"))) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", array("postgresql-contrib"))) ),
        );
        $this->programDataFolder = "/opt/PostgresServer"; // command and app dir name
        $this->programNameMachine = "postgresql"; // command and app dir name
        $this->programNameFriendly = "Postgres Server!"; // 12 chars
        $this->programNameInstaller = "Postgres Server";
        $this->statusCommand = "psql --version" ;
        $this->versionInstalledCommand = "sudo apt-cache policy postgresql" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy postgresql" ;
        $this->versionLatestCommand = "sudo apt-cache policy postgresql" ;
        $this->initialize();
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 25, 14) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 53, 14) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 53, 14) ;
        return $done ;
    }


}