<?php

Namespace Info;

class PHPConfInfo extends CleopatraBase {

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

//    public function dependencies() {
//        return array( "PHPConf" =>  array_merge(parent::dependencies(), array("Templating") ) );
//    }

    public function autoPilotVariables() {
      return array(
        "PHPConf" => array(
          "PHPConf" => array(
            "programDataFolder" => "/etc/phpconf/", // command and app dir name
            "programNameMachine" => "phpconf", // command and app dir name
            "programNameFriendly" => "PHP Conf.", // 12 chars
            "programNameInstaller" => "PHP Conf",
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and provides you  with a method by which you can install Apache HTTP Server

  PHPConf, php-configure, php-configuration, php-conf, phpconf

        - install
        Installs a configuration for PHP
        example: cleopatra phpconf install

HELPDATA;
      return $help ;
    }

}