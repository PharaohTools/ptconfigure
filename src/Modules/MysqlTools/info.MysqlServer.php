<?php

Namespace Info;

class MysqlToolsInfo extends Base {

  public $hidden = false;

  public $name = "MysqlTools";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "MysqlTools" =>  array_merge(parent::defaultActionsAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("mysql-tools"=>"MysqlTools", "mysqltools"=>"MysqlTools");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install some tools to help with MySQL Server

  MysqlTools, mysql-server, mysqlserver

        - install
        Installs Mysql Server through apt-get.
        example: cleopatra mysql-server install

HELPDATA;
    return $help ;
  }

}