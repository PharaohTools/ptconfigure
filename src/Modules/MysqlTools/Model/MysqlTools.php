<?php

Namespace Model;

class MysqlTools extends BaseLinuxApp {

  public function __construct($params) {
    parent::__construct($params);
    $this->autopilotDefiner = "MysqlTools";
    $this->installCommands = array( "apt-get install -y mysql-workbench" );
    $this->uninstallCommands = array( "apt-get remove -y mysql-workbench" );
    $this->programDataFolder = "/opt/MysqlTools"; // command and app dir name
    $this->programNameMachine = "mysqltools"; // command and app dir name
    $this->programNameFriendly = "MySQL Tools!"; // 12 chars
    $this->programNameInstaller = "MySQL Tools";
    $this->initialize();
  }

}