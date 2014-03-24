<?php

Namespace Model;

class MysqlToolsUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "MysqlTools";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "mysql-workbench")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "mytop")) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "mysql-workbench")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "mytop")) ),
        );
        $this->programDataFolder = "/opt/MysqlTools"; // command and app dir name
        $this->programNameMachine = "mysqltools"; // command and app dir name
        $this->programNameFriendly = "MySQL Tools!"; // 12 chars
        $this->programNameInstaller = "MySQL Tools";
        $this->initialize();
    }

    public function askStatus() {
        return $this->askStatusByArray( array("mysql-workbench", "mytop")) ;
    }

}