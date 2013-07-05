<?php

Namespace Info;

class DatabaseInfo extends Base {

    public $hidden = false;

    public $name = "Database Management Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Database" => array_merge(parent::routesAvailable(),
        array("install", "drop", "configure", "config", "conf", "reset", "useradd", "userdrop") ) );
    }

    public function routeAliases() {
      return array("db"=>"Database", "database"=>"Database");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and handles Databasing Functions.

  Database, database, db

          - configure, conf
          set up db user & pw for a project, use admins to create new resources as needed.
          example: dapperstrano db conf drupal

          - reset
          reset current db to generic values so dapperstrano can write them. may need to be run before db conf.
          example: dapperstrano db reset drupal

          - install
          install the database for a project. run conf first to set up users unless you already have them.
          example: dapperstrano db install

          - drop
          drop the database for a project.
          example: dapperstrano db drop

HELPDATA;
      return $help ;
    }

}