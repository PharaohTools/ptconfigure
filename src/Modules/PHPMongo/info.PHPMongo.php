<?php

Namespace Info;

class PHPMongoInfo extends PTConfigureBase {

  public $hidden = false;

  public $name = "PHP Modules - Commonly used PHP Modules";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "PHPMongo" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("php-mongo"=>"PHPMongo", "phpmongo"=>"PHPMongo");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This module allows you to install some common and helpful PHP Modules.

  PHPMongo, php-mongo, phpmongo, php-mongo, phpmongo

        - install
        Installs PHP Mongo PHP Module.
        example: ptconfigure phpmongo install -yg

HELPDATA;
    return $help ;
  }

}