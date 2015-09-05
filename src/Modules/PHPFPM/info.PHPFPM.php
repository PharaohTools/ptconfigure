<?php

Namespace Info;

class PHPFPMInfo extends PTConfigureBase {

  public $hidden = false;

  public $name = "PHP Modules - Commonly used PHP Modules";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "PHPFPM" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("php-mods"=>"PHPFPM", "phpmods"=>"PHPFPM", "php-modules"=>"PHPFPM",
      "PHPFPM"=>"PHPFPM");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This module allows you to install some common and helpful PHP Modules.

  PHPFPM, php-mods, phpmods, php-modules, PHPFPM

        - install
        Installs some common PHP Modules. These include php5-gd the image libs,
        php5-imagick the image libs, php5-curl the remote file handling libs,
        php5-mysql the libs for handling mysql connections.
        example: ptconfigure phpmods install

HELPDATA;
    return $help ;
  }

}