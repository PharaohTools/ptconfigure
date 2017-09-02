<?php

Namespace Info;

class ISOPHPInfo extends Base {

    public $hidden = false;

    public $name = "ISOPHP - Integration and Templates for ISOPHP";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        return array( "ISOPHP" => array_merge(parent::routesAvailable(), array('create') ) );
    }

    public function routeAliases() {
        return array( "isophp"=>"ISOPHP");
    }

    public function builderfyActions() {
        return array( "isophp" );
    }

    public function dapperfyActions() {
        return array( "isophp", "isophp-ptvirtualize", "isophp-ptvirtualize" );
    }

    public function dbConfigureActions() {
        return array( "isophp-conf", "isophp-reset" );
    }

    public function dbInstallActions() {
        return array( "isophp-save" );
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
  This module is a Default one, and provides integration for the ISOPHP Framework from Pharaoh Tools. This integration
  will allow you to create new projects. 
  
  ptdeploy isophp create
  
  
  
  
  It has tailored Builderfy and Dapperfy
  Autopilots and also provides ISOPHP Database Configuration for the DBConfigure Module.

  ISOPHP, isophp

  This module adds multiple actions to both builderfy and dapperfy. This will let you produce autopilots for both
  which are tailored to ISOPHP.

  // dapperfy - create our auto deploy files
  ptdeploy dapperfy isophp --yes --guess

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
  ISOPHP Module:

  The ISOPHP module extends Builderfy by providing Templates for both the build and the autopilot to execute them from

  This module adds the 'isophp' action to builderfy and will let you produce autopilots for it are tailored to ISOPHP.

  // builderfy - create templates to install build
  sudo ptdeploy builderfy isophp --yes --jenkins-home="/var/lib/jenkins" --target-job-name="my-project-continuous" --project-description="This is the Continuous Delivery build for My Project" --primary-scm-url="http://146.185.129.66:8080/git/root/first-pharaoh-cd.git" --source-branch-spec="origin/master" --source-scm-url="http://146.185.129.66:8080/git/root/first-pharaoh-cd.git" --days-to-keep="-1" --amount-to-keep="10" --autopilot-test-invoke-install-file="build/config/ptdeploy/autopilots/tiny-staging-invoke-code-no-dbconf.php" --autopilot-prod-invoke-install-file="build/config/ptdeploy/autopilots/tiny-prod-invoke-code-no-dbconf.php" --error-email="phpengine@hotmail.co.uk" --only-autopilots

  // execute the build creator - you'll be using your jenkins/build environment here
  ptdeploy autopilot execute build/config/ptdeploy/builderfy/autopilots/*environment-name*-isophp-invoke-continuous.php

HELPDATA;
        return $help ;
    }

    public function dapperfyHelpDefinition() {
        $help = <<<"HELPDATA"

--------------
  ISOPHP Module:

  The ISOPHP module extends Dapperfy by providing Templates for automated deployment Autopilots that will be configured
  for your particular ISOPHP site. This module adds the 'isophp' action to dapperfy.

  - isophp, isophp
  create isophp tailored automated deployment ptdeploy autopilots
  example: ptdeploy dapperfy isophp --yes --guess

  - isophp-ptvirtualize, isophp-ptvirtualize
  create isophp tailored automated deployment ptdeploy autopilots for your PTVirtualize Virtual Machines
  example: ptdeploy dapperfy isophp-ptvirtualize --yes --guess
HELPDATA;
        return $help ;
    }

    public function dbConfigureHelpDefinition() {
        $help = <<<"HELPDATA"

--------------
  ISOPHP Module:

  The ISOPHP module extends DBConfigure by providing Templates for resetting or setting the configuration of a ISOPHP

  ISOPHP module adds the actions isophp-conf, isophp-reset to DBConfigure and will
  let you produce autopilots for it which are tailored to ISOPHP.

  ptdeploy dbconf isophp-conf --yes --guess
HELPDATA;
        return $help ;
    }

    public function dbInstallHelpDefinition() {
        $help = <<<"HELPDATA"

--------------
  ISOPHP Module:

  The ISOPHP module extends DBInstall by providing integration that allows it to use Database connections your ISOPHP
  application is already setup to use, so that you'll not need to find or enter details.

  ISOPHP module adds the action isophp-save to DBInstall and will let you save a ISOPHP database in a single command.

  ptdeploy dbinstall isophp-save -yg
  ptdeploy dbinstall isophp-save -yg
    --admin-user=root
    --admin-pass=root

HELPDATA;
        return $help ;
    }




}