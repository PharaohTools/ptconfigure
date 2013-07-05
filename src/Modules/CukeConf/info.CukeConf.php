<?php

Namespace Info;

class CukeConfInfo extends Base {

    public $hidden = false;

    public $name = "Cucumber Configuration";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "CukeConf" => array_merge(parent::routesAvailable(), array("conf", "reset") ) );
    }

    public function routeAliases() {
      return array("cukeconf"=>"CukeConf", "cukeConf"=>"CukeConf", "cuke"=>"CukeConf");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and provides you  with a method by which you can configure Cucumber configurations

  cukeconf, cuke

          - conf
          modify the url used for cucumber features testing
          example: devhelper cukeconf cli

          - reset
          reset cuke uri to generic values so devhelper can write them. may need to be run before cuke conf.
          example: devhelper cukeconf reset
HELPDATA;
      return $help ;
    }

}