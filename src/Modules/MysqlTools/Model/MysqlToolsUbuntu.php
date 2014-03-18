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
        $this->installCommands = array( "apt-get install -y mysql-workbench mytop" );
        $this->uninstallCommands = array( "apt-get remove -y mysql-workbench mytop" );
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