<?php

Namespace Info;

class PTDeployInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "PTDeploy - The PHP Automated Website Deployment tool";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PTDeploy" =>  parent::routesAvailable() );
    }

    public function routeAliases() {
      return array("dapper"=>"PTDeploy", "ptdeploy"=>"PTDeploy");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to update PTDeploy.

  PTDeploy, dapper, ptdeploy

        - install
        Installs the latest version of ptdeploy
        example: ptconfigure ptdeploy install

        - ensure
        Installs the latest version of ptdeploy, only if a version is not installed
        example: ptconfigure ptdeploy ensure

HELPDATA;
      return $help ;
    }

}