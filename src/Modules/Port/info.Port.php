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
        example: ptconfigure port process -yg --port="25"

        - is-responding
        Test if a port is responding
        example: ptconfigure port is-responding -yg --port="25" # No IP specified will guess 127.0.0.1
        example: ptconfigure port is-responding -yg --port="22" --ip=1.2.3.4
        example: ptconfigure port is-responding -yg --port="80" --ip=www.google.com
        example: ptconfigure port is-responding -yg --port="80" --hostname=www.google.com

        - until-responding
        Test if a port is listening for a set amount of time or until it begins listening
        example: ptconfigure port until-responding --yes --guess
            --port=25 # port to test
            --interval=5 # no seconds to wait between requests, will guess 2
            --max-wait=100 # no seconds in total to keep trying, will guess 60
            --host=www.google.com # Hostname and IP are interchangeable
        example: ptconfigure port until-responding -yg --port="22" --ip=www.google.com --interval=5 --max-wait=30
        example: ptconfigure port until-responding -yg --port="25" --ip=www.google.com --interval=5 --max-wait=30
        example: ptconfigure port until-responding -yg --port="80" --ip=www.google.com --interval=5 --max-wait=60
        example: ptconfigure port until-responding -yg --port="81" --ip=www.google.com --interval=5 --max-wait=100

HELPDATA;
      return $help ;
    }

}