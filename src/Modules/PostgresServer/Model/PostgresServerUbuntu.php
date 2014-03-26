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
        $this->programNameMachine = "postgresserver"; // command and app dir name
        $this->programNameFriendly = "Postgres Server!"; // 12 chars
        $this->programNameInstaller = "Postgres Server";
        $this->statusCommand = "postgres --version" ;
        $this->initialize();
    }

}