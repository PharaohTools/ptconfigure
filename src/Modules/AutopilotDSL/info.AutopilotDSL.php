<?php

Namespace Info;

class AutopilotDSLInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "PTConfigure AutopilotDSL - Simple Syntax for User Defined Installations";

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

  AutopilotDSL, autopilot-dsl, auto-dsl

    - install, execute, x
    execute all of the defined modules in your AutopilotDSL file
    example: ptconfigure autopilot install --autopilot-file=*path-to-file*
    example: ptconfigure auto x --af=*path-to-file* *

  Example:

Logging do log
  message is "A Test Autopilot"

Logging do log
  message is daveyraveythere2


HELPDATA;
        return $help ;
    }

}