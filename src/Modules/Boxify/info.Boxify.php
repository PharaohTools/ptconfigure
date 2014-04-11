<?php

Namespace Info;

class BoxifyInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Cleopatra Boxifyer - Configures servers for your project";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Boxify" => array("help", "standard") );
    }

    public function routeAliases() {
      return array("boxify"=>"Boxify");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of a default Module Core and provides you with a method by which you can
  create a standard set of Autopilot files for your project from the command line.


  Boxify, boxify

        - standard
        Populate your project with Servers from Cloud Hosts, or your own Module if provided.
        example: cleopatra boxify standard

HELPDATA;
      return $help ;
    }

}