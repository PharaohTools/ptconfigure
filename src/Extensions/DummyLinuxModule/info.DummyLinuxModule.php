<?php

Namespace Info;

class DummyLinuxModuleInfo extends Base {

  public $hidden = false;

  public $name = "Dummy Linux Module";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "DummyLinuxModule" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("DummyLinuxModule"=>"DummyLinuxModule", "dummylinuxmodule"=>"DummyLinuxModule");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This is a dummy Linux module that doesn't execute any commands.

  DummyLinuxModule, dummylinuxmodule

        - install
        Installs nothing
        example: ptconfigure dummylinuxmodule install

HELPDATA;
    return $help ;
  }

}