<?php

Namespace Info;

class CukeConfInfo {

    public $hidden = false;

    public $name = "Cucumber Configuration";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "cukeConf" => array("conf", "reset") );
    }

    public function routeAliases() {
      return array("cukeconf"=>"cukeConf");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and provides you  with a method by which you can configure Cucumber configurations
HELPDATA;
      return $help ;
    }

}