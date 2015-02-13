<?php

Namespace Info;

class PharaohToolsInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Pharaoh Tools - Gotta Install them all";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PharaohTools" =>  array_merge(parent::routesAvailable(), array() ) );
    }

    public function routeAliases() {
      return array("pharaohtools"=>"PharaohTools", "pharaoh-tools"=>"PharaohTools");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install the Pharaoh Tools which are ready. These include
  PTConfigure - this Configuration Management tool, PTDeploy - the Automated Deployment tool,
  and PTTest, the test configuration and automation tool.

  PharaohTools, pharaohtools, pharaoh-tools

        - install
        Installs the latest version of all of the Pharaoh Tools
        example: ptconfigure pharaoh-tools install

HELPDATA;
      return $help ;
    }

}