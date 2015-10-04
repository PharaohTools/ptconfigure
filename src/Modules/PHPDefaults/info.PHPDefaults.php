<?php

Namespace Info;

class PHPFPMInfo extends PTConfigureBase {

  public $hidden = false;

  public $name = "PHP Modules - Commonly used PHP Modules";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "PHPFPM" =>  array_merge(parent::routesAvailable(), array("install", "restart") ) );
  }

  public function routeAliases() {
    return array("php-fpm"=>"PHPFPM", "phpfpm"=>"PHPFPM", "PHPFPM"=>"PHPFPM");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This module allows you to install some common and helpful PHP Modules.

  PHPFPM, php-fpm, phpfpm

        - install
        Installs PHP FPM, the PHP Fast CGI Process Manager
        example: ptconfigure phpfpm install

        - restart
        Restarts PHP FPM. On some systems this is not available by calling service, so this
        version should work on any
        example: ptconfigure phpfpm restart

HELPDATA;
    return $help ;
  }

}