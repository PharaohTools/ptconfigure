<?php

Namespace Info;

class PHPConfInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "PHP Conf - Install a PHP Configuration";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PHPConf" =>  array_merge(parent::routesAvailable(), array() ) );
    }

    public function routeAliases() {
        return array("php-configuration"=>"PHPConf", "php-configure"=>"PHPConf", "php-conf"=>"PHPConf",
            "phpconf"=>"PHPConf");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module is part of the Default Distribution and provides you  with a method by which you can install Apache HTTP Server

  PHPConf, php-configure, php-configuration, php-conf, phpconf

        - install
        Installs a configuration for PHP
        example: ptconfigure phpconf install

HELPDATA;
      return $help ;
    }

}