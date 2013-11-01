<?php

Namespace Info;

class IndexInfo extends Base {

    public $hidden = true;

    public $name = "Index/Home Page";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Index" => array("index") );
    }

    public function routeAliases() {
      return array("index"=>"Index");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core - its the default route and only used for help and as an Intro really...
HELPDATA;
      return $help ;
    }

}