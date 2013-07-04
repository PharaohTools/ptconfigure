<?php

Namespace Info;

class InstallInfo {

    public $hidden = false;

    public $name = "Installation Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "install" => array("cli", "autopilot") );
    }

    public function routeAliases() {
      return array();
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and handles default Install.
HELPDATA;
      return $help ;
    }

}