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
        return array( "Port" => array("help", "status", "is-responding", "until-responding", "process") );
    }

    public function routeAliases() {
      return array("port"=>"Port");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to test the status of ports and services running on them

  Port, port

        - process
        See which process is using a port
        example: ptconfigure port process --port-number="25"

        - is-responding
        Test if a port is responding
        example: ptconfigure port is-responding --port-number="25"

        - until-responding
        Test if a port is listening for a set amount of time or until it begins listening
        example: ptconfigure port until-responding --yes --guess
            --port=25 # port to test
            --interval=5 # no seconds to wait between requests, will guess 2
            --max-wait=100 # no seconds in total to keep trying, will guess 60

HELPDATA;
      return $help ;
    }

}