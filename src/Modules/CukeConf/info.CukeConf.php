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
  This command is part of Default Modules and provides you  with a method by which you can configure Cucumber configurations

  CukeConf, cukeConf, cukeconf, cuke

          - configure, config, conf
          modify the url used for cucumber features testing
          example: dapperstrano cukeconf conf
          example: dapperstrano cukeconf conf --yes --cucumber-url="www.dave.local"

          - reset
          reset cuke uri to generic values so dapperstrano can write them. may need to be run before cuke conf.
          example: dapperstrano cukeconf reset
          example: dapperstrano cukeconf reset --yes

HELPDATA;
      return $help ;
    }

}