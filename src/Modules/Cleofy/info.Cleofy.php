<?php

Namespace Info;

class CleofyInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Cleopatra Cleofyer - Creates default autopilots for your project";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Cleofy" =>  array_merge(parent::routesAvailable(), array("standard") ) );
    }

    public function routeAliases() {
      return array("cleofy"=>"Cleofy");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of a default Module Core and provides you with a method by which you can
  create a standard set of Autopilot files for your project from the command line.


  Cleofy, cleofy

        - list
        List all of the autopilot files in your build/config/cleopatra/autopilots
        example: cleopatra cleofy list

        - standard
        Create a default set of cleopatra autopilots in build/config/cleopatra/autopilots for
        your project.
        example: cleopatra cleofy standard

HELPDATA;
      return $help ;
    }

}