<?php

Namespace Info;

class CleopatraRequiredInfo extends CleopatraBase {

    public $hidden = true;

    public $name = "Cleopatra Required Models";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "CleopatraRequired" =>  array_merge(parent::routesAvailable() ) );
    }

    public function routeAliases() {
      return array();
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module provides no commands, but is required for Cleopatra. It provides Models which are required for Cleopatra.


HELPDATA;
      return $help ;
    }

}