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
      return array("cleo"=>"PTTest", "pttest"=>"PTTest");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to update PTTest.

  PTTest, cleo, pttest

        - install
        Installs the latest version of pttest
        example: pttest pttest install

HELPDATA;
      return $help ;
    }

}