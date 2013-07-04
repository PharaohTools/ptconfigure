<?php

Namespace Info;

class InvokeInfo {

    public $hidden = false;

    public $name = "SSH Invocation Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "invoke" => array("cli", "script", "autopilot")  );
    }

    public function routeAliases() {
      return array();
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and handles SSH Connection Functions.
HELPDATA;
      return $help ;
    }

}