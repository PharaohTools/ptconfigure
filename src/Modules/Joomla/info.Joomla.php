<?php

Namespace Info;

class JoomlaInfo extends Base {

    public $hidden = false;

    public $name = "Joomla - Integration and Templates for Joomla";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        return array( "Joomla" => array_merge(parent::routesAvailable(), array() ) );
    }

    public function routeAliases() {
        return array( "joomla"=>"Joomla");
    }

    public function builderfyActions() {
        return array( "joomla" );
    }

    public function dapperfyActions() {
        return array( "joomla", "joomla15", "joomla30", "joomla30-phlagrant", "joomla-phlagrant" );
    }

    public function dbConfigureActions() {
        return array( "joomla30-conf", "joomla15-conf", "joomla30-reset", "joomla15-reset" );
    }

    public function helpDefinitions() {
        return array(
            "Builderfy"=>$this->builderfyHelpDefinition(),
            "Dapperfy"=>$this->dapperfyHelpDefinition(),
            "DBConfigure"=>$this->dbConfigureHelpDefinition());
    }

    public function helpDefinition() {
        $help = <<<"HELPDATA"
  This module is a Default one, and provides integration for Joomla websites. It has tailored Builderfy and Dapperfy
  Autopilots and also provides Joomla Database Configuration for the DBConfigure Module.

  Joomla, joomla

  This module adds multiple actions to both builderfy and dapperfy. This will let you produce autopilots for both
  which are tailored to Joomla.

  // dapperfy - create our auto deploy files
  dapperstrano dapperfy joomla --yes --guess

  // builderfy - create templates to install build
  sudo dapperstrano builderfy continuous --yes --jenkins-home="/var/lib/jenkins" --target-job-name="my-project-continuous" --project-description="This is the Continuous Delivery build for My Project" --primary-scm-url="http://146.185.129.66:8080/git/root/first-pharaoh-cd.git" --source-branch-spec="origin/master" --source-scm-url="http://146.185.129.66:8080/git/root/first-pharaoh-cd.git" --days-to-keep="-1" --amount-to-keep="10" --autopilot-test-invoke-install-file="build/config/dapperstrano/autopilots/tiny-staging-invoke-code-no-dbconf.php" --autopilot-prod-invoke-install-file="build/config/dapperstrano/autopilots/tiny-prod-invoke-code-no-dbconf.php" --error-email="phpengine@hotmail.co.uk" --only-autopilots

  // execute the build creator
  dapperstrano autopilot execute build/config/dapperstrano/builderfy/autopilots/tiny-jenkins-invoke-continuous.php

HELPDATA;
        return $help ;
    }

    public function builderfyHelpDefinition() {
        $help = <<<"HELPDATA"

--------------
  Joomla Module:

  The Joomla module extends Builderfy by providing Templates for both the build and the autopilot to execute them from

  This module adds the 'joomla' action to builderfy and will let you produce autopilots for it are tailored to Joomla.

  // builderfy - create templates to install build
  sudo dapperstrano builderfy joomla --yes --jenkins-home="/var/lib/jenkins" --target-job-name="my-project-continuous" --project-description="This is the Continuous Delivery build for My Project" --primary-scm-url="http://146.185.129.66:8080/git/root/first-pharaoh-cd.git" --source-branch-spec="origin/master" --source-scm-url="http://146.185.129.66:8080/git/root/first-pharaoh-cd.git" --days-to-keep="-1" --amount-to-keep="10" --autopilot-test-invoke-install-file="build/config/dapperstrano/autopilots/tiny-staging-invoke-code-no-dbconf.php" --autopilot-prod-invoke-install-file="build/config/dapperstrano/autopilots/tiny-prod-invoke-code-no-dbconf.php" --error-email="phpengine@hotmail.co.uk" --only-autopilots

  // execute the build creator - you'll be using your jenkins/build environment here
  dapperstrano autopilot execute build/config/dapperstrano/builderfy/autopilots/*environment-name*-joomla-invoke-continuous.php

HELPDATA;
        return $help ;
    }

    public function dapperfyHelpDefinition() {
        $help = <<<"HELPDATA"

--------------
  Joomla Module:

  The Joomla module extends Dapperfy by providing Templates for automated deployment Autopilots that will be configured
  for your particular Joomla site. This module adds the 'joomla' action to dapperfy.

  - joomla, joomla30
  create joomla tailored automated deployment dapperstrano autopilots
  example: dapperstrano dapperfy joomla --yes --guess

  - joomla-phlagrant, joomla30-phlagrant
  create joomla tailored automated deployment dapperstrano autopilots for your Phlagrant Virtual Machines
  example: dapperstrano dapperfy joomla-phlagrant --yes --guess
HELPDATA;
        return $help ;
    }

    public function dbConfigureHelpDefinition() {
        $help = <<<"HELPDATA"

--------------
  Joomla Module:

  The Joomla module extends DBConfigure by providing Templates for resetting or setting the configuration of a Joomla

  Joomla module adds the actions joomla30-conf, joomla30-reset, joomla15-conf, joomla15-reset to DBConfigure and will
  let you produce autopilots for it which are tailored to Joomla.

  dapperstrano dbconf joomla30-conf --yes --guess
HELPDATA;
        return $help ;
    }




}