<?php

Namespace Info;

class LoggingInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Logging - Output errors to the logging";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Logging" =>  array_merge( array("help", "log") ) );
    }

    public function routeAliases() {
        return array("logging"=>"Logging");
    }

    public function exposedParams() {
      return array(
        "Log" => array(
            "log" => array(
                "log-message" => "The message that you would like to be output to the log",
                "php-log" => "An empty value is fine. Sets This message should go to the PHP Error Log as well as the Console", ) ,
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  Use this to log a message to the Console, and optionally the php error log.

  Logging, logging

        - log
        Logs a message the logging or
        example: cleopatra logging log --php-log --log-message="Here is something logging to the console and error log"

HELPDATA;
      return $help ;
    }

}