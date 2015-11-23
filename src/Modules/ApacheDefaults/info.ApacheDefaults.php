<?php

Namespace Info;

class ApacheDefaultsInfo extends PTConfigureBase {

  public $hidden = false;

  public $name = "Apache Defaults - Default PHP Configurations and Files";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "ApacheDefaults" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("apache-defaults"=>"ApacheDefaults", "apachedefaults"=>"ApacheDefaults", "apachedef"=>"ApacheDefaults");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This module allows you to install default settings and files for PHP.

  ApacheDefaults, apache-defaults, apachedefaults, apachedef

        - install
        Installs Apache Default Settings
        example: ptconfigure ApacheDefaults install


HELPDATA;
    return $help ;
  }

}