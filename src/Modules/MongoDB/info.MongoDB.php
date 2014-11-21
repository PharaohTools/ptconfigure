<?php

Namespace Info;

class MongoDBInfo extends CleopatraBase {

  public $hidden = false;

  public $name = "MongoDB Server - The MongoDB Datastore Server";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "MongoDB" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("mongo-db-server"=>"MongoDB", "mongodb-server"=>"MongoDB", "mongodbserver"=>"MongoDB");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install the MongoDB Server. Currently only
  MongoDB Workbench, the Database management GUI provided by Oracle for
  MongoDB.

  MongoDB, mongo-db-server, mongodb-server, mongodbserver, mongodb, mongo-db

        - install
        Install MongoDB Server
        example: cleopatra mongodb install

  Notes, during mongodb install a root password will be set. First, it'll look
  for the parameter --mongodb-root-pass, if this is not set, it'll look in the
  cleopatra config for a mongodb-default-root-pass setting, and failing both of
  those it will just set the password for root to cleopatra.

HELPDATA;
    return $help ;
  }

}