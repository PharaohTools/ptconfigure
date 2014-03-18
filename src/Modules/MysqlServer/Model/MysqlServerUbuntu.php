<?php

Namespace Model;

class MysqlServerUbuntu extends BaseLinuxApp {

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
        $newRootPass = $this->getNewRootPass();
        $this->autopilotDefiner = "MysqlServer";
        $this->installCommands = array(
            "echo mysql-server mysql-server/root_password password $newRootPass | sudo debconf-set-selections",
            "echo mysql-server mysql-server/root_password_again password $newRootPass | sudo debconf-set-selections",
            "apt-get install -y mysql-client mysql-server"
        );
        $this->uninstallCommands = array( "apt-get remove -y mysql-client mysql-server" );
        $this->programDataFolder = "/opt/MysqlServer"; // command and app dir name
        $this->programNameMachine = "mysqlserver"; // command and app dir name
        $this->programNameFriendly = "MySQL Server!"; // 12 chars
        $this->programNameInstaller = "MySQL Server";
        $this->statusCommand = "sudo mysql --version" ;
        $this->initialize();
    }

    private function getNewRootPass() {
        if (isset($this->params["mysql-root-pass"])) {
            $newRootPass = $this->params["mysql-root-pass"] ; }
        else if (AppConfig::getProjectVariable("mysql-default-root-pass") != "") {
            $newRootPass = AppConfig::getProjectVariable("mysql-default-root-pass") ; }
        else {
            $newRootPass = "cleopatra" ; }
        return $newRootPass;
    }

}