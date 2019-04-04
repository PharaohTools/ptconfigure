<?php

Namespace Info;

class AutopilotInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Autopilot - User Defined Installations";

    public function __construct() {
        parent::__construct();
    }

    public function routesAvailable() {
        return array( "Autopilot" =>  array_merge(parent::routesAvailable(), array("install", "execute", "x", "test") ) );
    }

    public function routeAliases() {
        return array("auto"=>"Autopilot", "autopilot"=>"Autopilot");
    }

    public function helpDefinition() {
        $help = "This command is part of a default Module and provides you with a method by which you can perform user defined
  executions of any ".PHARAOH_APP." Modules, in any order, and with your own predefined settings.

  Autopilot, autopilot, auto

    - install, execute, x
    execute all of the defined modules in your Autopilot file
    example: ".PHARAOH_APP." autopilot install --autopilot-file=*path-to-file*
    example: ".PHARAOH_APP." auto x --af=*path-to-file* 
    example: ".PHARAOH_APP." auto x --af=*path-to-file*.dsl.php # In Pharaoh DSL Format
    example: ".PHARAOH_APP." auto x --af=*path-to-file*.dsl.yml # In Yaml Format
    example: ".PHARAOH_APP." auto x --af=*path-to-file*.dsl.yaml # In Yaml Format

    - test
    execute all of the steps defined as tests in your Autopilot file
    example: ".PHARAOH_APP." auto test --af=*path-to-file*";
        return $help ;
    }

}