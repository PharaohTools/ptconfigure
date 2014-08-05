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
        ping a target once
        example: cleopatra ping once --yes --guess
            --target=www.google.com # url/ip to ping test
            --interval=5 # no seconds to wait between requests, will guess 2

        - ten
        ping a target ten times
        example: cleopatra ping ten --yes --guess
            --target=www.google.com # url/ip to ping test
            --interval=5 # no seconds to wait between requests, will guess 2

        - until-responding
        ping a target for a set amount of time until it responds
        example: cleopatra ping once --yes --guess
            --target=www.google.com # url/ip to ping test
            --interval=5 # no seconds to wait between requests, will guess 2
            --max-wait=100 # no seconds in total to keep trying, will guess 60

HELPDATA;
      return $help ;
    }

}