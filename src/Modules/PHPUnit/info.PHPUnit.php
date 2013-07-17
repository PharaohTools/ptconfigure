<?php

Namespace Info;

class PHPUnitInfo extends Base {

    public $hidden = false;

    public $name = "PHPUnit";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PHPUnit" =>  array_merge(parent::defaultActionsAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("phpunit"=>"PHPUnit", "phpUnit"=>"PHPUnit", "php-unit"=>"PHPUnit");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install PHPUnit from a GC Repo.

  PHPUnit

        - install
        Installs the latest GC Repo version of PHPUnit
        example: cleopatra phpunit install

HELPDATA;
      return $help ;
    }

}