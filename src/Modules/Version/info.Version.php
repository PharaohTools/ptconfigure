<?php

Namespace Info;

class VersionInfo {

    public $hidden = false;

    public $name = "Versioning Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "version" => array("cli", "latest", "rollback", "specific") );
    }

    public function routeAliases() {
      return array();
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and handles Application Versioning, allowing for rollbacks and the like.
HELPDATA;
      return $help ;
    }

}