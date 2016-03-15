<?php

Namespace Info;

class AutopilotDSLInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Autopilot DSL - A Simple Syntax for Autopilots";

    public function __construct() {
        parent::__construct();
    }

    public function routesAvailable() {
        return array( "AutopilotDSL" => array("help") );
    }

    public function routeAliases() {
        return array("auto-dsl"=>"AutopilotDSL", "autopilot-dsl"=>"AutopilotDSL");
    }

    public function helpDefinition() {
        $help = <<<"HELPDATA"
  This DSL for Autopilots allows for your Autopilots to be defined in a highly streamlined syntax.

  This module does not provide any commands other than help

  Use a file extension of .dsl.php to let the program know your Autopilot is written in DSL syntax.


  DSL Example:

Logging do log
  message is "A Test Autopilot"

Logging do log
  message is daveysthere2


HELPDATA;
        return $help ;
    }

}