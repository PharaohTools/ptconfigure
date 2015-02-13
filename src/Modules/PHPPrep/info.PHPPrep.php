<?php

Namespace Info;

class PHPPrepInfo extends PTConfigureBase {

    public $hidden = true;

    public $name = "PTConfigure PHPPrepper - Install PHP to prepare for Pharaoh install";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PHPPrep" =>  array_merge(parent::routesAvailable(), array("standard") ) );
    }

    public function routeAliases() {
      return array("phpprep"=>"PHPPrep");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of a default Module and provides files for installing PHP.

  PHPPrep, phpprep

        - standard
        Holds PHP
        your project.
        example: ptconfigure phpprep standard

HELPDATA;
      return $help ;
    }

}