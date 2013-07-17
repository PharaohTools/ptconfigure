<?php

Namespace Info;

class PHPModulesInfo extends Base {

  public $hidden = false;

  public $name = "PHPModules";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "PHPModules" =>  array_merge(parent::defaultActionsAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("php-mods"=>"PHPModules", "phpmods"=>"PHPModules", "php-modules"=>"PHPModules",
      "phpmodules"=>"PHPModules");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install some common and helpful PHP Modules.

  PHPModules, php-mods, phpmods, php-modules, phpmodules

        - install
        Installs some common PHP Modules. These include php5-gd the image libs,
        php5-imagick the image libs, php5-curl the remote file handling libs,
        php5-mysql the libs for handling mysql connections.
        example: cleopatra phpmods install

HELPDATA;
    return $help ;
  }

}