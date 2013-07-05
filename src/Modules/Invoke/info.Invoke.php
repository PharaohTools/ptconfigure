<?php

Namespace Info;

class InvokeInfo extends Base {

    public $hidden = false;

    public $name = "SSH Invocation Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Invoke" => array_merge(parent::routesAvailable(), array("cli", "script", "autopilot") ) );
    }

    public function routeAliases() {
      return array("invoke" => "Invoke", "inv" => "Invoke");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and handles SSH Connection Functions.

  Invoke, invoke, inv

        - cli
        Will ask you for details for servers, then open a shell for you to execute on multiple servers
        example: devhelper invoke shell

        - script
        Will ask you for details for servers, then execute each line of a provided script file on the remote/s
        example: devhelper invoke script script-file

        - autopilot
        execute each line of a script file, multiple script files, or php variable data on one or more remotes
        example: devhelper invoke autopilot autopilot-file

HELPDATA;
      return $help ;
    }

}