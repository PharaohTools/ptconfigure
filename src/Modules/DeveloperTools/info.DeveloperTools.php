<?php

Namespace Info;

class DeveloperToolsInfo extends Base {

    public $hidden = false;

    public $name = "DeveloperTools";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "DeveloperTools" =>  array_merge(parent::defaultActionsAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("devtools"=>"DeveloperTools", "dev-tools"=>"DeveloperTools");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install a set of Developer Tools. These include
  Geany IDE, Bluefish IDE, Kompozer IDE and Emma DB Manager.

  DeveloperTools, devtools, dev-tools

        - install
        Installs the latest version of Developer Tools
        example: cleopatra devtools install

HELPDATA;
      return $help ;
    }

}