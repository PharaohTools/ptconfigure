<?php

Namespace Info;

class PTConfigureRequiredInfo extends PTConfigureBase {

    public $hidden = true;

    public $name = "PTConfigure Required Models";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PTConfigureRequired" =>  array_merge(parent::routesAvailable() ) );
    }

    public function routeAliases() {
      return array();
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module provides no commands, but is required for PTConfigure. It provides Models which are required for PTConfigure.


HELPDATA;
      return $help ;
    }

}