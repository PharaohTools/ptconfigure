<?php

Namespace Info;

class HostEditorInfo extends Base {

    public $hidden = false;

    public $name = "Host File Management Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "HostEditor" => array("add", "rm", "help"));
    }

    public function routeAliases() {
      return array("he"=>"HostEditor", "hostEditor"=>"HostEditor");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Default Modules and handles Host File Management Functions.

  HostEditor, hosteditor, he, hostEditor

          - add
          add a Host File entry
          example: ptdeploy hosteditor add
          example: ptdeploy hosteditor add --yes
              --host-ip=127.0.0.1  # guess will assume 127.0.0.1
              --host-name=dave.com

          - rm
          remove a Host File entry
          example: ptdeploy hosteditor rm
          example: ptdeploy hosteditor rm --yes
              --host-name=dave.com

HELPDATA;
      return $help ;
    }

}