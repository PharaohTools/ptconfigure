<?php

Namespace Info;

class HostEditorInfo {

    public $hidden = false;

    public $name = "Host File Management Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "hostEditor" => array("add", "rm") );
    }

    public function routeAliases() {
      return array("he"=>"hostEditor");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and handles Host File Management Functions.
HELPDATA;
      return $help ;
    }

}