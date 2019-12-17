<?php

Namespace Info;

class BuilderfyInfo extends Base {

    public $hidden = false;

    public $name = "PTDeploy Builderfyer - Create some standard autopilots for your project";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Builderfy" =>  array_merge(
          parent::routesAvailable(),
          array(
              "install-generic-autopilots", "developer", "manual-staging", "continuous-staging", "manual-production",
              "continuous-staging-to-production" ),
          $this->getExtraRoutes()
      ) );
    }

    public function routeAliases() {
      return array("builderfy"=>"Builderfy");
    }

    public function dependencies() {
        return array("EnvironmentConfig");
    }

    public function helpDefinition() {
      $extraHelp = $this->getExtraHelpDefinitions() ;
      $help = <<<"HELPDATA"
  This is a default Module and provides you a way to deploy build jobs to jenkins that are configured for your project.

  Builderfy, builderfy

        - developer
        Create a developers build for this project
        example: ptdeploy builderfy developer
        example: ptdeploy builderfy developer
                    --yes
                    --guess (optional)
                    --project-description="A description for the project"
                    --github-url="http://www.github.com/phpengine/ptdeploy"
                    --source-branch-spec="origin/master" # guess will assume origin/master
                    --source-scm-url="/var/www/application" # guess will assume the current directory
                    --days-to-keep="10" # guess will assume -1 (to ignore this value)
                    --num-to-keep="100" # guess will assume 15
                    --autopilot-install="/path/to/installer/autopilot" # guess will assume "build/config/ptdeploy/autopilots/autopilot-dev-jenkins-install.php"
                    --autopilot-uninstall="/path/to/uninstaller/autopilot" # guess will assume "build/config/ptdeploy/autopilots/autopilot-dev-jenkins-uninstall.php"
                    --target-scm-url="http://www.github.com/phpengine/ptdeploy" #  guess will use your github url
                    --target-branch="master" # guess will default to master

        - manual-staging
        Create a build which will manually deploy to staging and optionally test this project.
        example: ptdeploy builderfy manual-staging

        - continuous-staging
        Create a build which will test and automatically deploy to staging for this project, triggered by SCM Changes.
        example: ptdeploy builderfy continuous-staging

        - manual-production
        Create a build which will manually deploy to production for this project. It deploys straight to production, so
        will not test first.
        example: ptdeploy builderfy manual-staging

        - continuous-staging-to-production
        Create a continuous build job for this project, which will automatically deploy and test SCM Changes to the
        staging server, and upon successful testing will also deploy those changes to production.
        example: ptdeploy builderfy continuous-staging-to-production
        ptdeploy builderfy continuous --yes --jenkins-home="/var/lib/jenkins" --target-job-name="my-project-continuous" --project-description="This is the Continuous Delivery build for My Project" --primary-scm-url="http://146.185.129.66:8080/git/root/first-pharaoh-cd.git" --source-branch-spec="origin/master" --source-scm-url="http://146.185.129.66:8080/git/root/first-pharaoh-cd.git" --days-to-keep="-1" --amount-to-keep="10" --autopilot-test-invoke-install-file="build/config/ptdeploy/autopilots/tiny-staging-invoke-code-no-dbconf.php" --autopilot-prod-invoke-install-file="build/config/ptdeploy/autopilots/tiny-prod-invoke-code-no-dbconf.php" --error-email="phpengine@hotmail.co.uk" --only-autopilots

        also --no-autopilots to just install the build

        $extraHelp
HELPDATA;
      return $help ;
    }

    protected function getExtraHelpDefinitions() {
        $extraDefsText = "" ;
        $infos = \Core\AutoLoader::getInfoObjects() ;
        foreach ($infos as $info) {
            if (method_exists($info, "helpDefinitions")) {
                $defNames = array_keys($info->helpDefinitions());
                if (in_array("Builderfy", $defNames)) {
                    $defs = $info->helpDefinitions() ;
                    $thisDef = $defs["Builderfy"] ;
                    $extraDefsText .= $thisDef ; } } }
        return $extraDefsText ;
    }

    protected function getExtraRoutes() {
        $extraActions = array() ;
        $infos = \Core\AutoLoader::getInfoObjects() ;
        foreach ($infos as $info) {
            if (method_exists($info, "builderfyActions")) {
                $extraActions = array_merge($extraActions, $info->builderfyActions()); } }
        return $extraActions ;
    }

}