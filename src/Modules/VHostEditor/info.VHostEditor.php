<?php

Namespace Info;

class VHostEditorInfo {

    public $hidden = false;

    public $name = "Apache Virtual Host Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "VHostEditor" => array("add", "rm", "list")  );
    }

    public function routeAliases() {
      return array("vhc"=>"VHostEditor");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and handles Apache VHosts Functions.
HELPDATA;
      return $help ;
    }

}