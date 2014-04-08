<?php

Namespace Info;

class MysqlServerInfo extends CleopatraBase {

  public $hidden = false;

  public $name = "Mysql Server - The Mysql RDBMS Server";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "MysqlServer" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("mysql-server"=>"MysqlServer", "mysqlserver"=>"MysqlServer");
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

  Notes, during mysql install a root password will be set. First, it'll look
  for the parameter --mysql-root-pass, if this is not set, it'll look in the
  cleopatra config for a mysql-default-root-pass setting, and failing both of
  those it will just set the password for root to cleopatra.

HELPDATA;
    return $help ;
  }

}