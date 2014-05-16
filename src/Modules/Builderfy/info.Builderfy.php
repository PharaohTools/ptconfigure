<?php

Namespace Info;

class BuilderfyInfo extends Base {

    public $hidden = false;

    public $name = "Dapperstrano Builderfyer - Create some standard autopilots for your project";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Builderfy" =>  array_merge(parent::routesAvailable(), array("developer", "staging", "continuous", "production") ) );
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
        dapperstrano builderfy continuous --yes --jenkins-home="/var/lib/jenkins" --target-job-name="my-project-continuous" --project-description="This is the Continuous Delivery build for My Project" --primary-scm-url="http://146.185.129.66:8080/git/root/first-pharoah-cd.git" --source-branch-spec="origin/master" --source-scm-url="http://146.185.129.66:8080/git/root/first-pharoah-cd.git" --days-to-keep="-1" --amount-to-keep="10" --autopilot-test-invoke-install-file="build/config/dapperstrano/autopilots/tiny-staging-invoke-code-no-dbconf.php" --autopilot-prod-invoke-install-file="build/config/dapperstrano/autopilots/tiny-prod-invoke-code-no-dbconf.php" --error-email="phpengine@hotmail.co.uk" --only-autopilots

        also --no-autopilots to just install the build


HELPDATA;
      return $help ;
    }

}