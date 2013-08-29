<?php

Namespace Info;

class InstallInfo {

    public $hidden = true;

    public $name = "Install Page";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Install" => array() );
    }

    public function routeAliases() {
      return array("install"=>"Install");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core - for handling install...
HELPDATA;
      return $help ;
    }

}