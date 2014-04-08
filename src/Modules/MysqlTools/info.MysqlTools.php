<?php

Namespace Info;

class MysqlToolsInfo extends CleopatraBase {

  public $hidden = false;

  public $name = "Mysql Tools - For administering and developing with Mysql";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "MysqlTools" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("mysql-tools"=>"MysqlTools", "mysqltools"=>"MysqlTools");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install some tools to help with MySQL Server. Installs the MySQL
  Workbench GUI and also the mytop Command Line Tool.

  MysqlTools, mysql-tools, mysqltools

        - install
        Installs Mysql Tools through apt-get.
        example: cleopatra mysql-tools install

HELPDATA;
    return $help ;
  }

}