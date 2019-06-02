<?php

Namespace Info;

class VariableGroupsInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Use Variable Files in Autopilots.";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "VariableGroups" =>  array_merge(parent::routesAvailable(), array("dump") ) );
    }

    public function routeAliases() {
      return array("VariableGroups"=>"VariableGroups", "variablegroups"=>"VariableGroups");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to use Variable Files in Autopilots.

  VariableGroups, variablegroups

        - dump
        Dump all existing Variables to console
        example: ptconfigure variablegroups dump
        example: ptconfigure variablegroups dump -yg
        example: ptconfigure variablegroups dump -yg --source="" --target=""

HELPDATA;
      return $help ;
    }

}