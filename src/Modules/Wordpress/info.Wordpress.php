<?php

Namespace Info;

class WordpressInfo extends Base {

    public $hidden = false;

    public $name = "Wordpress - Integration and Templates fo Wordpress";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        return array( "Wordpress" => array_merge(parent::routesAvailable(), array() ) );
    }

    public function routeAliases() {
        return array( "wordpress"=>"Wordpress");
    }

    public function builderfyActions() {
        return array( "wordpress" );
    }

    public function dapperfyActions() {
        return array( "wordpress");
    }

    public function dbConfigureActions() {
        return array("wordpress-conf", "wordpress-reset");
    }

    public function helpDefinitions() {
        return array(
            "Builderfy"=>$this->builderfyHelpDefinition(),
            "Dapperfy"=>$this->dapperfyHelpDefinition(),
            "DBConfigure"=>$this->dbConfigureHelpDefinition());
    }

    public function helpDefinition() {
        $help = <<<"HELPDATA"
  This module is a Default one, and provides integration for Wordpress websites. It has tailored Builderfy and Dapperfy
  Autopilots and also provides Wordpress Database Configuration for the DBConfigure Module.

  Wordpress, wordpress

  This module adds multiple actions to both builderfy and dapperfy. This will let you produce autopilots for both
  which are tailored to Wordpress.

  // dapperfy - create our auto deploy files
  dapperstrano dapperfy wordpress --yes --guess

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
  Wordpress Module:

  The Wordpress module extends Builderfy by providing Templates for both the build and the autopilot to execute them from

  This module adds the 'wordpress' action to builderfy and will let you produce autopilots for it are tailored to Wordpress.

  // builderfy - create templates to install build
  sudo dapperstrano builderfy wordpress --yes --jenkins-home="/var/lib/jenkins" --target-job-name="my-project-continuous" --project-description="This is the Continuous Delivery build for My Project" --primary-scm-url="http://146.185.129.66:8080/git/root/first-pharoah-cd.git" --source-branch-spec="origin/master" --source-scm-url="http://146.185.129.66:8080/git/root/first-pharoah-cd.git" --days-to-keep="-1" --amount-to-keep="10" --autopilot-test-invoke-install-file="build/config/dapperstrano/autopilots/tiny-staging-invoke-code-no-dbconf.php" --autopilot-prod-invoke-install-file="build/config/dapperstrano/autopilots/tiny-prod-invoke-code-no-dbconf.php" --error-email="phpengine@hotmail.co.uk" --only-autopilots

  // execute the build creator - you'll be using your jenkins/build environment here
  dapperstrano autopilot execute build/config/dapperstrano/builderfy/autopilots/*environment-name*-wordpress-invoke-continuous.php

HELPDATA;
        return $help ;
    }

    public function dapperfyHelpDefinition() {
        $help = <<<"HELPDATA"

--------------
  Wordpress Module:

  The Wordpress module extends Dapperfy by providing Templates for automated deployment Autopilots that will be configured
  for your particular Wordpress site. This module adds the 'wordpress' action to dapperfy.

  - wordpress
  create wordpress tailored automated deployment dapperstrano autopilots
  example: dapperstrano dapperfy wordpress --yes --guess
HELPDATA;
        return $help ;
    }

    public function dbConfigureHelpDefinition() {
        $help = <<<"HELPDATA"

--------------
  Wordpress Module:

  The Wordpress module extends DBConfigure by providing Templates for resetting or setting the configuration of a Wordpress

  Wordpress module adds the actions wordpress-conf, wordpress-reset to DBConfigure and will let you produce autopilots
  for it which are tailored to Wordpress.

  dapperstrano dbconf wordpress-conf --yes --guess
HELPDATA;
        return $help ;
    }




}
