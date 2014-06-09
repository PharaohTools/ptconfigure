<?php

Namespace Info;

class ThoughtWorksGoInfo extends CleopatraBase {

  public $hidden = false;

  public $name = "Mysql Server Galera - The Galera Clustering compatible version of Mysql RDBMS Server";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "ThoughtWorksGo" =>  array_merge(parent::routesAvailable(), array("install", "config-galera-starter") ) );
  }

  public function routeAliases() {
    return array("mysql-server-galera"=>"ThoughtWorksGo", "mysqlservergalera"=>"ThoughtWorksGo");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install the MySQL Server Galera version.

  ThoughtWorksGo, mysql-server-galera, mysqlservergalera

        - install
        Install the Galera Cluster compatible version of Mysql Server
        example: cleopatra mysql-server-galera install

        - config-galera-starter
        Configure the wsrep.cnf file for a cluster starter
        example: cleopatra mysql-server-galera config-galera-starter

        - config-galera-joiner
        Configure the wsrep.cnf file for a cluster joiner
        example: cleopatra mysql-server-galera config-galera-joiner


  Notes, during mysql install a root password will be set. First, it'll look
  for the parameter --mysql-root-pass, if this is not set, it'll look in the
  cleopatra config for a mysql-default-root-pass setting, and failing both of
  those it will just set the password for root to cleopatra.

HELPDATA;
    return $help ;
  }

}