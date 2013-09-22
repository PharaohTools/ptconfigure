<?php

Namespace Model;

class MysqlServer extends BaseLinuxApp {

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
        $this->initialize();
    }

    private function getNewRootPass() {
        if (isset($this->params["mysql-root-pass"])) {
            $newRootPass = $this->params["mysql-root-pass"] ; }
        else if (AppConfig::getProjectVariable("mysql_default_root_pass") != "") {
            $newRootPass = AppConfig::getProjectVariable("mysql_default_root_pass") ; }
        else {
            $newRootPass = "cleopatra" ; }
        return $newRootPass;
    }

}