<?php

Namespace Info;

class ParallaxInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Parallax - Execute commands in parallel";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Parallax" =>  array_merge(parent::routesAvailable(), array("cli", "child") ) );
    }

    public function routeAliases() {
      return array("parallax"=>"Parallax");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"

  This Module lets you execute commands in parallel

  Parallax, parallax

        - cli
        Go through all questions to execute parallel programs
        example: cleopatra parallax cli
        example: cleopatra parallax cli --yes --command-1="pwd" --command-2="ls"

        - child
        Unlikely you'll use this, its used by cli to spawn child processes
        example: parallax cli child

HELPDATA;
      return $help ;
    }

}