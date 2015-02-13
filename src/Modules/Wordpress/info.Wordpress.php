<?php

Namespace Info;

class WordpressInfo extends Base {

    public $hidden = false;

    public $name = "Wordpress - Integration and Templates for Wordpress";

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

    public function dbInstallActions() {
        return array("wordpress-install", "wp-install");
    }

    public function helpDefinitions() {
        return array(
            "Builderfy"=>$this->builderfyHelpDefinition(),
            "Dapperfy"=>$this->dapperfyHelpDefinition(),
            "DBConfigure"=>$this->dbConfigureHelpDefinition(),
            "DBInstall"=>$this->dbInstallHelpDefinition());
    }

    public function helpDefinition() {
        $help = <<<"HELPDATA"
  This module is a Default one, and provides integration for Wordpress websites. It has tailored Builderfy and Dapperfy
  Autopilots and also provides Wordpress Database Configuration for the DBConfigure Module.

  Wordpress, wordpress

  This module adds multiple actions to both builderfy and dapperfy. This will let you produce autopilots for both
  which are tailored to Wordpress.

  // dapperfy - create our auto deploy files
  ptdeploy dapperfy wordpress --yes --guess

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
  Wordpress Module:

  The Wordpress module extends Builderfy by providing Templates for both the build and the autopilot to execute them from

  This module adds the 'wordpress' action to builderfy and will let you produce autopilots for it are tailored to Wordpress.

  // builderfy - create templates to install build
  sudo ptdeploy builderfy wordpress --yes --jenkins-home="/var/lib/jenkins" --target-job-name="my-project-continuous" --project-description="This is the Continuous Delivery build for My Project" --primary-scm-url="http://146.185.129.66:8080/git/root/first-pharaoh-cd.git" --source-branch-spec="origin/master" --source-scm-url="http://146.185.129.66:8080/git/root/first-pharaoh-cd.git" --days-to-keep="-1" --amount-to-keep="10" --autopilot-test-invoke-install-file="build/config/ptdeploy/autopilots/tiny-staging-invoke-code-no-dbconf.php" --autopilot-prod-invoke-install-file="build/config/ptdeploy/autopilots/tiny-prod-invoke-code-no-dbconf.php" --error-email="phpengine@hotmail.co.uk" --only-autopilots

  // execute the build creator - you'll be using your jenkins/build environment here
  ptdeploy autopilot execute build/config/ptdeploy/builderfy/autopilots/*environment-name*-wordpress-invoke-continuous.php

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
  create wordpress tailored automated deployment ptdeploy autopilots
  example: ptdeploy dapperfy wordpress --yes --guess
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

  ptdeploy dbconf wordpress-conf --yes --guess
HELPDATA;
        return $help ;
    }

    public function dbInstallHelpDefinition() {
        $help = <<<"HELPDATA"

--------------
  Wordpress Module:

  The Wordpress module extends DBInstall by adding wordpress-install

  Wordpress module adds the actions wordpress-install and wp-install to DBInstall, requiresd to allow the Post DB
  Install hooks for Wordpress, the DB restore won't work correctly without at least the url.

  ptdeploy dbinstall wordpress-install --yes --guess --hook-url=www.site.env
HELPDATA;
        return $help ;
    }




}
