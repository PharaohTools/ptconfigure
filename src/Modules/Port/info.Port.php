<?php

Namespace Info;

class PortInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Test a Port to see which process is listening on it";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "Port" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "Port" => array("help", "status", "is-responding", "process") );
    }

    public function routeAliases() {
      return array("port"=>"Port");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to test the status of ports and services running on them

  Port, port

        - is-responding
        Test if a port is responding
        example: ptconfigure port is-responding --port-number="25"

        - process
        See which process is using a port
        example: ptconfigure port process --port-number="25"

HELPDATA;
      return $help ;
    }

}