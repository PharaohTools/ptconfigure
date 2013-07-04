<?php

Namespace Info;

class DatabaseInfo {

    public $hidden = false;

    public $name = "Database Management Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "database" => array("install", "drop", "configure", "config", "conf", "reset", "useradd", "userdrop")  );
    }

    public function routeAliases() {
      return array("db"=>"database");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and handles Databasing Functions.
HELPDATA;
      return $help ;
    }

}