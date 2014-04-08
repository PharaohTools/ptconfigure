<?php

Namespace Info;

class PortInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Test a Port to see if its responding";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "Port" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "Port" => array("help", "status", "is-responding") );
    }

    public function routeAliases() {
      return array("port"=>"Port");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to test the status of ports

  Port, port

        - is-responding
        Test if a port is responding
        example: cleopatra port is-responding --port-number="25"

HELPDATA;
      return $help ;
    }

}