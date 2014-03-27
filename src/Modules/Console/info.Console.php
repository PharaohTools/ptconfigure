<?php

Namespace Info;

class ConsoleInfo extends Base {

    public $hidden = false;

    public $name = "Console - Output errors to the console";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Console" =>  array_merge( array("help", "log") ) );
    }

    public function routeAliases() {
        return array("console"=>"Console");
    }

    public function autoPilotVariables() {
      return array(
        "Console" => array(
            "log" => array(
                "console-log-message" => "The message that you would like to be output to the log" ) ,
            "log-and-error" => array(
                "console-log-message" => "The message that you would like to be output to the log" ,
                "error-level" => "The kind of error to raise. Used with log-and-error. Defaults to low if not provided."
                . "Values can be high, medium or low" ) ,
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command provides you with a way to output to the Console log

  Console, console

        - log
        Logs a message to PHP STDERR
        example: cleopatra console log

HELPDATA;
      return $help ;
    }

}