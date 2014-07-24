<?php

Namespace Info;

class AutopilotInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Cleopatra Autopilot - User Defined Installations";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Autopilot" =>  array_merge(parent::routesAvailable(), array("install", "execute", "x") ) );
    }

    public function routeAliases() {
      return array("auto"=>"Autopilot", "autopilot"=>"Autopilot");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of a default Module and provides you with a method by
  which you can perform user defined executions of any Cleopatra Modules, in
  any order, and with your own predefined settings.

  Autopilot, autopilot, auto

    - install, execute, x
    execute all of the defined modules in your Autopilot file
    example: cleopatra autopilot x --autopilot-file=*path-to-file*

HELPDATA;
      return $help ;
    }

}