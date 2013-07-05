<?php

Namespace Info;

class HostEditorInfo extends Base {

    public $hidden = false;

    public $name = "Host File Management Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "HostEditor" => array_merge(parent::routesAvailable(), array("add", "rm") ) );
    }

    public function routeAliases() {
      return array("he"=>"HostEditor", "hostEditor"=>"HostEditor");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and handles Host File Management Functions.

  hosteditor

          - add
          add a Host File entry
          example: devhelper hosteditor add

          - rm
          remove a Host File entry
          example: devhelper hosteditor rm

HELPDATA;
      return $help ;
    }

}