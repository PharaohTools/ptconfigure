<?php

Namespace Info;

class AutopilotYAMLInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Autopilot YAML - A Simple Syntax for Autopilots";

    public function __construct() {
        parent::__construct();
    }

    public function routesAvailable() {
        return array( "AutopilotYAML" => array("help") );
    }

    public function routeAliases() {
        return array("auto-yaml"=>"AutopilotYAML", "autopilot-yaml"=>"AutopilotYAML");
    }

    public function helpDefinition() {
        $help = <<<"HELPDATA"
  This YAML for Autopilots allows for your Autopilots to be defined in a highly streamlined syntax.

  This module does not provide any commands other than help

  Use a file extension of .dsl.php to let the program know your Autopilot is written in YAML syntax.


  YAML Example:

- Logging[log]:
    message: "This is the first Pharaoh Yaml DSL"
    guess: true

- Logging[log]:
    message: "is dave there"


HELPDATA;
        return $help ;
    }

}