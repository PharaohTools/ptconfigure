<?php

Namespace Info;

class DrupalInfo extends Base {

    public $hidden = false;

    public $name = "Drupal - Integration and Templates for Drupal";

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
        // return array( "drupal" );
        return array( "drupal", "drupal7", "drupal-ptvirtualize", "drupal7-ptvirtualize" );
    }

    public function dbConfigureActions() {
        return array( "drupal7-conf", "drupal6-conf", "drupal7-reset", "drupal6-reset" );
    }

    public function helpDefinitions() {
        return array(
            "Builderfy"=>$this->builderfyHelpDefinition(),
            "Dapperfy"=>$this->dapperfyHelpDefinition(),
            "DBConfigure"=>$this->dbConfigureHelpDefinition());
    }

    public function helpDefinition() {
        $help = <<<"HELPDATA"
  This module is a Default Modules and provides autopilots for drupal tailored Builderfy and Dapperfy Autopilots.
  Also provides Drupal Database Configuration for the DBConfigure Module.

  Drupal, drupal

  This module adds multiple actions to both builderfy and dapperfy. This will let you produce autopilots for both
  which are tailored to Drupal.

  // dapperfy - create our auto deploy files
  ptdeploy dapperfy drupal --yes --guess

  // builderfy - create templates to install build
  sudo ptdeploy builderfy continuous --yes --jenkins-home="/var/lib/jenkins" --target-job-name="my-project-continuous" --project-description="This is the Continuous Delivery build for My Project" --primary-scm-url="http://146.185.129.66:8080/git/root/first-pharaoh-cd.git" --source-branch-spec="origin/master" --source-scm-url="http://146.185.129.66:8080/git/root/first-pharaoh-cd.git" --days-to-keep="-1" --amount-to-keep="10" --autopilot-test-invoke-install-file="build/config/ptdeploy/autopilots/tiny-staging-invoke-code-no-dbconf.php" --autopilot-prod-invoke-install-file="build/config/ptdeploy/autopilots/tiny-prod-invoke-code-no-dbconf.php" --error-email="phpengine@hotmail.co.uk" --only-autopilots

  // execute the build creator
  ptdeploy autopilot execute build/config/ptdeploy/builderfy/autopilots/tiny-jenkins-invoke-continuous.php

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
  sudo ptdeploy builderfy drupal --yes --jenkins-home="/var/lib/jenkins" --target-job-name="my-project-continuous" --project-description="This is the Continuous Delivery build for My Project" --primary-scm-url="http://146.185.129.66:8080/git/root/first-pharaoh-cd.git" --source-branch-spec="origin/master" --source-scm-url="http://146.185.129.66:8080/git/root/first-pharaoh-cd.git" --days-to-keep="-1" --amount-to-keep="10" --autopilot-test-invoke-install-file="build/config/ptdeploy/autopilots/tiny-staging-invoke-code-no-dbconf.php" --autopilot-prod-invoke-install-file="build/config/ptdeploy/autopilots/tiny-prod-invoke-code-no-dbconf.php" --error-email="phpengine@hotmail.co.uk" --only-autopilots

  // execute the build creator - you'll be using your jenkins/build environment here
  ptdeploy autopilot execute build/config/ptdeploy/builderfy/autopilots/*environment-name*-drupal-invoke-continuous.php

HELPDATA;
        return $help ;
    }

    public function dapperfyHelpDefinition() {
        $help = <<<"HELPDATA"

--------------
  Drupal Module:

  The Drupal module extends Dapperfy by providing Templates for automated deployment Autopilots that will be configured
  for your particular Drupal site. This module adds the 'drupal' action to dapperfy.

  - drupal
  create drupal tailored automated deployment ptdeploy autopilots
  example: ptdeploy dapperfy drupal --yes --guess

HELPDATA;
        return $help ;
    }

    public function dbConfigureHelpDefinition() {
        $help = <<<"HELPDATA"

--------------
  Drupal Module:

  The Drupal module extends DBConfigure by providing Templates for resetting or setting the configuration of a Drupal

  Drupal module adds the actions drupal7-conf, drupal7-reset, drupal6-conf, drupal6-reset to DBConfigure and will
  let you produce autopilots for it which are tailored to Drupal.

  ptdeploy dbconf drupal7-conf --yes --guess

HELPDATA;
        return $help ;
    }




}