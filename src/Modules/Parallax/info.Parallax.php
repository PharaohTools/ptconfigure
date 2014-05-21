<?php

Namespace Info;

class ParallaxInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Parallax - Execute commands in parallel";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Parallax" =>  array_merge(parent::routesAvailable(), array("parent") ) );
    }

    public function routeAliases() {
      return array("parallax"=>"Parallax");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"

  This Module lets you execute commands in parallel

  Parallax, parallax

        - execute
        Go through all questions to execute parallel programs
        example: parallax cli execute

HELPDATA;
      return $help ;
    }

}