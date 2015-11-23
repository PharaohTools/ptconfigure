<?php

Namespace Info;

class PHPDefaultsInfo extends PTConfigureBase {

  public $hidden = false;

  public $name = "PHP Defaults - Default PHP Configurations and Files";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "PHPDefaults" =>  array_merge(parent::routesAvailable(), array("install", "restart") ) );
  }

  public function routeAliases() {
    return array("php-defaults"=>"PHPDefaults", "phpdefaults"=>"PHPDefaults", "phpdef"=>"PHPDefaults");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This module allows you to install default settings and files for PHP.

  PHPDefaults, php-defaults, PHPDefaults

        - install
        Installs PHP Default Settings
        example: ptconfigure PHPDefaults install


HELPDATA;
    return $help ;
  }

}