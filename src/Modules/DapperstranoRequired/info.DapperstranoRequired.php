<?php

Namespace Info;

class DapperstranoRequiredInfo extends Base {

    public $hidden = true;

    public $name = "Dapperstrano Required Models";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "DapperstranoRequired" =>  array_merge(parent::routesAvailable() ) );
    }

    public function routeAliases() {
      return array();
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module provides no commands, but is required for Dapperstrano. It provides Models which are required for Dapperstrano.


HELPDATA;
      return $help ;
    }

}