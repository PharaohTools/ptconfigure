<?php

Namespace Info;

class PTBuildInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "PTBuild - Upgrade or Re-install PTBuild";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PTBuild" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("ptbuild"=>"PTBuild");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to update PTBuild.

  PTBuild, ptbuild

        - install
        Installs the latest version of ptbuild
        example: ptconfigure ptbuild install

HELPDATA;
      return $help ;
    }

}