<?php

Namespace Info;

class PHPLDAPInfo extends PTConfigureBase {

  public $hidden = false;

  public $name = "PHP Modules - Commonly used PHP Modules";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "PHPLDAP" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("php-mods"=>"PHPLDAP", "phpmods"=>"PHPLDAP", "php-modules"=>"PHPLDAP",
      "phpmodules"=>"PHPLDAP");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This module allows you to install some common and helpful PHP Modules.

  PHPLDAP, php-mods, phpmods, php-modules, phpmodules

        - install
        Installs some common PHP Modules. These include php5-gd the image libs,
        php5-imagick the image libs, php5-curl the remote file handling libs,
        php5-mysql the libs for handling mysql connections.
        example: ptconfigure phpmods install

HELPDATA;
    return $help ;
  }

}