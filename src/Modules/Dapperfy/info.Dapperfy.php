<?php

Namespace Info;

class DapperfyInfo extends Base {

    public $hidden = false;

    public $name = "Dapperstrano Dapperfyer - Create some standard autopilots for your project";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Dapperfy" =>  array_merge(parent::routesAvailable(), array("create", "standard") ) );
    }

    public function routeAliases() {
      return array("dapperfy"=>"Dapperfy");
    }

    public function dependencies() {
        return array("EnvironmentConfig");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of a default Module Core and provides you with a method by which you can
  create a standard set of Autopilot files for your project from the command line.
  You can configure default application settings, ie: mysql admin user, host, pass


  Dapperfy, dapperfy

        - list
        List all of the autopilot files in your build/config/dapperstrano/autopilots
        example: dapperstrano dapperfy list

        - standard
        Create a standard set of autopilots to manage
        example: dapperstrano dapperfy standard

        The start of the command will be dapperstrano autopilot execute *filename*


HELPDATA;
      return $help ;
    }

}