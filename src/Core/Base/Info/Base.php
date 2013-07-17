<?php

Namespace Info;

class Base {

    public $hidden ;

    public $name ;

    public function __construct() {
    }

    public function defaultActionsAvailable() {
      return array("help");
    }

    public function routeAliases() {
      return array();
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  There is no help defined for this module
HELPDATA;
      return $help ;
    }

}