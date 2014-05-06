<?php

Namespace Info;

class EnvironmentConfigInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Environment Configuration - Configure Environments for a project";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "EnvironmentConfig" =>  array_merge(parent::routesAvailable(), array("list", "configure", "config", "delete", "del") ) );
    }

    public function routeAliases() {
      return array("environmentconfig"=>"EnvironmentConfig", "environment-config"=>"EnvironmentConfig",
        "envconfig"=>"EnvironmentConfig", "env-config"=>"EnvironmentConfig");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of a default Module and provides you with a method by which you can
  configure environments for your project from the command line. Currently compliant with
  both Dapperstrano and Cleopatra.


  EnvironmentConfig, environmentconfig, environment-config, envconfig, env-config

        - list
        List current environments
        example: cleopatra envconfig list --yes

        - configure, config
        Configure the environments for your project to use
        example: cleopatra envconfig config
        example: cleopatra envconfig config --keep-current-environments

        - delete, del
        Configure the environments for your project to use
        example: cleopatra envconfig delete
        example: cleopatra envconfig del --environment-name="staging"


HELPDATA;
      return $help ;
    }

}