<?php

Namespace Info;

class JoomlaTasksInfo extends PTConfigureBase {

  public $hidden = false;

  public $name = "Joomla Tasks - Basic Tasks for productivity in Joomla";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "JoomlaTasks" =>  array("help"));
  }

  public function routeAliases() {
    return array("joomlatasks"=>"JoomlaTasks");
  }

  public function taskActions() {
      return array("saveptvdb");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This module provides some Joomla CMS tasks

  Task, task

        - saveptvdb
        Use this command to save the database in your ptv machine
        example: ptconfigure task saveptvdb
        example: ptconfigure task saveptvdb

HELPDATA;
    return $help ;
  }

}