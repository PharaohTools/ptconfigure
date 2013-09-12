<?php

Namespace Model;

class MysqlServer extends BaseLinuxApp {

  public function __construct($params) {
    parent::__construct($params);
    $this->autopilotDefiner = "MysqlServer";
    $this->installCommands = array( "apt-get install -y mysql-client mysql-server" );
    $this->uninstallCommands = array( "apt-get remove -y mysql-client mysql-server" );
    $this->programDataFolder = "/opt/MysqlServer"; // command and app dir name
    $this->programNameMachine = "mysqlserver"; // command and app dir name
    $this->programNameFriendly = "MySQL Server!"; // 12 chars
    $this->programNameInstaller = "MySQL Server";
    $this->initialize();
  }

}