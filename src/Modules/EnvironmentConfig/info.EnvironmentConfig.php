<?php

Namespace Info;

class EnvironmentConfigInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Environment Configuration - Configure Environments for a project";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "EnvironmentConfig" =>  array_merge(parent::routesAvailable(), array(
          "list", "list-local", "configure", "config", "delete", "del", "configure-default", "config-default") ) );
    }

    public function routeAliases() {
      return array("environmentconfig"=>"EnvironmentConfig", "environment-config"=>"EnvironmentConfig",
        "envconfig"=>"EnvironmentConfig", "env-config"=>"EnvironmentConfig");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of a default Module and provides you with a method by which you can
  configure environments for your project from the command line. Currently compliant with
  both PTDeploy and PTConfigure.


  EnvironmentConfig, environmentconfig, environment-config, envconfig, env-config

        - list
        List current environments
        example: ptconfigure envconfig list --yes

        - list-local
        List current local environments
        example: ptconfigure envconfig list-local --yes

        - configure, config
        Configure bespoke environments for your project to use
        example: ptconfigure envconfig config
        # below to create an empty environment to add instances to
        example: ptconfigure envconfig config --yes
                    --keep-current-environments # do not overwrite the current environments stored in papyrusfile
                    --no-manual-servers # so it will not ask you to interactively enter connection details of instances
                    --add-single-environment # otherwise it will loop for more until you specify not to
                    --environment-name="some-name" # name of the environment to create
                    --tmp-dir=/tmp/ # we're deprecating this soon

        - configure-default, config-default
        Configure default environments for your project to use
        example: ptconfigure envconfig config-default
        example: ptconfigure envconfig config-default --yes --environment-name="local-80/local-8080"

        - delete, del
        Configure the environments for your project to use
        example: ptconfigure envconfig delete
        example: ptconfigure envconfig del --environment-name="staging"


HELPDATA;
      return $help ;
    }

}