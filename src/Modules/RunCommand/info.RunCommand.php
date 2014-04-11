<?php

Namespace Info;

class RunCommandInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Execute a Command";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "RunCommand" =>  array("execute", "help") );
    }

    public function routeAliases() {
      return array("runcommand"=>"RunCommand", "run-command"=>"RunCommand");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This allows you to execute an Operating System command. This would primarily be used in an Autopilot.

  RunCommand, runcommand, run-command

        - execute
        Execute a Command
        example: cleopatra run-command --yes --command="ls -lah /tmp" --run-as-user="ubuntu" --background

HELPDATA;
      return $help ;
    }

}