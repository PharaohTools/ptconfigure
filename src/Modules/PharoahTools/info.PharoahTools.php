<?php

Namespace Info;

class PharoahToolsInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Pharoah Tools - Gotta Install them all";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PharoahTools" =>  array_merge(parent::routesAvailable(), array() ) );
    }

    public function routeAliases() {
      return array("pharoahtools"=>"PharoahTools", "pharoah-tools"=>"PharoahTools");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install the Pharoah Tools which are ready. These include
  Cleopatra - this Configuration Management tool, Dapperstrano - the Automated Deployment tool,
  and Testingkamen, the test configuration and automation tool.

  PharoahTools, pharoahtools, pharoah-tools

        - install
        Installs the latest version of all of the Pharoah Tools
        example: cleopatra pharoah-tools install

HELPDATA;
      return $help ;
    }

}