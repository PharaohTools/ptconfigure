<?php

Namespace Info;

class PTDeployRequiredInfo extends Base {

    public $hidden = true;

    public $name = "PTDeploy Required Models";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PTDeployRequired" =>  array_merge(parent::routesAvailable() ) );
    }

    public function routeAliases() {
      return array();
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module provides no commands, but is required for PTDeploy. It provides Models which are required for PTDeploy.


HELPDATA;
      return $help ;
    }

}