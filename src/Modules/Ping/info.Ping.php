<?php

Namespace Info;

class PingInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Test a Ping to see if its responding";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "Ping" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "Ping" => array("help", "once", "ten", "until-responding") );
    }

    public function routeAliases() {
      return array("ping"=>"Ping");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to test the status of ports

  Ping, ping

        - once
        ping a target
        example: cleopatra port is-responding --port-number="25"

HELPDATA;
      return $help ;
    }

}