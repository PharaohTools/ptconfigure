<?php

Namespace Info;

class Base {

    public $hidden ;

    public $name ;

    public function __construct() {
    }

    public function routesAvailable() {
      return array("help", "status", "install", "uninstall", "initialize", "init", "execute");
      // @todo some app settings class should contain this instead
    }

    public function routeAliases() {
        return array();
    }

    public function dependencies() {
        return array();
    }

    public function groups() {
        return array();
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  There is no help defined for this module
HELPDATA;
      return $help ;
    }

}