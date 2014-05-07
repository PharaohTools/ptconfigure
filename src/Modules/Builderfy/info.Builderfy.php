<?php

Namespace Info;

class BuilderfyInfo extends Base {

    public $hidden = false;

    public $name = "Dapperstrano Builderfyer - Create some standard autopilots for your project";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Builderfy" =>  array_merge(parent::routesAvailable(), array("developer", "staging", "continuous-staging", "production", "continuous-production") ) );
    }

    public function routeAliases() {
      return array("builderfy"=>"Builderfy");
    }

    public function dependencies() {
        return array("EnvironmentConfig");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This is a default Module and provides you a way to deploy build jobs to jenkins that are configured for your project.

  Builderfy, builderfy

        - developer
        Create a developers build for this project
        example: dapperstrano builderfy developer

        - staging
        Create a developers build for this project
        example: dapperstrano builderfy staging

        - production
        Create a developers build for this project
        example: dapperstrano builderfy production

        - continuous
        Create a continuous build for this project
        example: dapperstrano builderfy continuous


HELPDATA;
      return $help ;
    }

}