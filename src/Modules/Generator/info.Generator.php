<?php

Namespace Info;

class GeneratorInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "PTDeploy Autopilot Generator - Generate Autopilot files interactively";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Generator" =>  array_merge(parent::routesAvailable(), array("create") ) );
    }

    public function routeAliases() {
      return array("generator"=>"Generator", "generate"=>"Generator", "gen"=>"Generator");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of the Default Distribution and provides you with a method by which you can
  create Autopilot files from the command line.
  You can configure default application settings, ie: mysql admin user, host, pass

  Generator, generator, generate, gen

        - create
        Go through all modules to create an autopilot
        example: ptdeploy generate create

HELPDATA;
      return $help ;
    }

}