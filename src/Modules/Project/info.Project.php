<?php

Namespace Info;

class ProjectInfo {

    public $hidden = false;

    public $name = "Project Management Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "project" => array("init", "build-install", "container", "cont") );
    }

    public function routeAliases() {
      return array("proj"=>"project");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and handles Project initialisation functions, like configuring a project, or a project
  container and also installing Jenkins build files into a running Jenkins instance.
HELPDATA;
      return $help ;
    }

}