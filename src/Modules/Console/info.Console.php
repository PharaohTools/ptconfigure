<?php

Namespace Info;

class ConsoleInfo extends Base {

    public $hidden = false;

    public $name = "Console - Output errors to the console";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Console" =>  array_merge( array("log") ) );
    }

    public function routeAliases() {
        return array("console"=>"Console");
    }

    public function autoPilotVariables() {
      return array(
        "Console" => array(
          "Console" => array(
            "programDataFolder" => "/etc/console/", // command and app dir name
            "programNameMachine" => "console", // command and app dir name
            "programNameFriendly" => "Console", // 12 chars
            "programNameInstaller" => "Console",
          )
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