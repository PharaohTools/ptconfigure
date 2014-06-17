<?php

Namespace Info;

class DrupalInfo extends Base {

    public $hidden = false;

    public $name = "Drupal - Integration and Templates fo Drupal";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        return array( "Drupal" => array_merge(parent::routesAvailable(), array() ) );
    }

    public function routeAliases() {
        return array("drupal"=>"Drupal");
    }

    public function builderfyActions() {
        return array( "drupal" );
    }

    public function dapperfyActions() {
        return array( "drupal" );
    }

    public function dbConfigureActions() {
        return array( "drupal7", "drupal6" );
    }

    public function helpDefinitions() {
        return array("Builderfy"=>$this->builderfyHelpDefinition(), "Dapperfy"=>$this->dapperfyHelpDefinition());
    }

    public function helpDefinition() {
        $help = <<<"HELPDATA"
  This module is a Default Modules and provides autopilots for drupal tailored Builderfy and Dapperfy Autopilots.
  Also provides Drupal Database Configuration for the DBConfigure Module.

  Drupal, drupal

  This module adds multiple actions to both builderfy and dapperfy. This will let you produce autopilots for both
  which are tailored to Drupal.

  // dapperfy - create our auto deploy files
  dapperstrano dapperfy drupal --yes --guess

  // builderfy - create templates to install build
  sudo dapperstrano builderfy continuous --yes --jenkins-home="/var/lib/jenkins" --target-job-name="my-project-continuous" --project-description="This is the Continuous Delivery build for My Project" --primary-scm-url="http://146.185.129.66:8080/git/root/first-pharoah-cd.git" --source-branch-spec="origin/master" --source-scm-url="http://146.185.129.66:8080/git/root/first-pharoah-cd.git" --days-to-keep="-1" --amount-to-keep="10" --autopilot-test-invoke-install-file="build/config/dapperstrano/autopilots/tiny-staging-invoke-code-no-dbconf.php" --autopilot-prod-invoke-install-file="build/config/dapperstrano/autopilots/tiny-prod-invoke-code-no-dbconf.php" --error-email="phpengine@hotmail.co.uk" --only-autopilots

  // execute the build creator
  dapperstrano autopilot execute build/config/dapperstrano/builderfy/autopilots/tiny-jenkins-invoke-continuous.php

HELPDATA;
        return $help ;
    }

    public function builderfyHelpDefinition() {
        $help = <<<"HELPDATA"

--------------
  Drupal Module:

  The Drupal module extends Builderfy by providing Templates for both the build and the autopilot to execute them from

  This module adds the 'drupal' action to builderfy and will let you produce autopilots for it are tailored to Drupal.

  // builderfy - create templates to install build
  sudo dapperstrano builderfy drupal --yes --jenkins-home="/var/lib/jenkins" --target-job-name="my-project-continuous" --project-description="This is the Continuous Delivery build for My Project" --primary-scm-url="http://146.185.129.66:8080/git/root/first-pharoah-cd.git" --source-branch-spec="origin/master" --source-scm-url="http://146.185.129.66:8080/git/root/first-pharoah-cd.git" --days-to-keep="-1" --amount-to-keep="10" --autopilot-test-invoke-install-file="build/config/dapperstrano/autopilots/tiny-staging-invoke-code-no-dbconf.php" --autopilot-prod-invoke-install-file="build/config/dapperstrano/autopilots/tiny-prod-invoke-code-no-dbconf.php" --error-email="phpengine@hotmail.co.uk" --only-autopilots

  // execute the build creator - you'll be using your jenkins/build environment here
  dapperstrano autopilot execute build/config/dapperstrano/builderfy/autopilots/*environment-name*-drupal-invoke-continuous.php

HELPDATA;
        return $help ;
    }

    public function dapperfyHelpDefinition() {
        $help = <<<"HELPDATA"

--------------
  Drupal Module:

  The Drupal module extends Dapperfy by providing Templates for both the build and the autopilot to execute them from

  This module adds the 'drupal' action to dapperfy and will let you produce autopilots for it which are tailored to Drupal.

  dapperstrano dapperfy drupal --yes --guess

HELPDATA;
        return $help ;
    }




}