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
        example: dapperstrano builderfy developer
                    --yes
                    --guess (optional)
                    --project-description="A description for the project"
                    --github-url="http://www.github.com/phpengine/dapperstrano"
                    --source-branch-spec="origin/master" # guess will assume origin/master
                    --source-scm-url="/var/www/application" # guess will assume the current directory
                    --days-to-keep="10" # guess will assume -1 (to ignore this value)
                    --num-to-keep="100" # guess will assume 15
                    --autopilot-install="/path/to/installer/autopilot" # guess will assume "build/config/dapperstrano/autopilots/autopilot-dev-jenkins-install.php"
                    --autopilot-uninstall="/path/to/uninstaller/autopilot" # guess will assume "build/config/dapperstrano/autopilots/autopilot-dev-jenkins-uninstall.php"
                    --target-scm-url="http://www.github.com/phpengine/dapperstrano" #  guess will use your github url
                    --target-branch="master" # guess will default to master

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