<?php

Namespace Info;

class PTTestInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Upgrade or Re-install PTTest";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PTTest" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("pttest"=>"PTTest");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to update PTTest.

  PTTest, pttest

        - install
        Installs the latest version of pttest
        example: ptconfigure pttest install

HELPDATA;
      return $help ;
    }

}