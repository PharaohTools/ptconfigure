<?php

Namespace Info;

class MysqlServerInfo extends Base {

  public $hidden = false;

  public $name = "MysqlServer";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "MysqlServer" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("mysql-server"=>"MysqlServer", "mysqlserver"=>"MysqlServer");
  }

  public function autoPilotVariables() {
    return array(
      "MysqlServer" => array(
        "MysqlServer" => array(
          "programDataFolder" => "/opt/MysqlServer", // command and app dir name
          "programNameMachine" => "mysqlserver", // command and app dir name
          "programNameFriendly" => "Mysql Server!", // 12 chars
          "programNameInstaller" => "Mysql Server",
        ),
      )
    );
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install the MySQL Server. Currently only
  Mysql Workbench, the Database management GUI provided by Oracle for
  Mysql.

  MysqlServer, mysql-server, mysqlserver

        - install
        Install some Mysql Server Tools through apt-get.
        example: cleopatra mysql-server install

HELPDATA;
    return $help ;
  }

}