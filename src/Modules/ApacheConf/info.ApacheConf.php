<?php

Namespace Info;

class ApacheConfInfo extends Base {

    public $hidden = false;

    public $name = "PHP Conf - Install a PHP Configuration";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "ApacheConf" =>  array_merge(parent::routesAvailable(), array() ) );
    }

    public function routeAliases() {
        return array("php-configuration"=>"ApacheConf", "php-configure"=>"ApacheConf", "php-conf"=>"ApacheConf",
            "apacheconf"=>"ApacheConf");
    }

    public function autoPilotVariables() {
      return array(
        "ApacheConf" => array(
          "ApacheConf" => array(
            "programDataFolder" => "/etc/apacheconf/", // command and app dir name
            "programNameMachine" => "apacheconf", // command and app dir name
            "programNameFriendly" => "PHP Conf.", // 12 chars
            "programNameInstaller" => "PHP Conf",
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and provides you  with a method by which you can install Apache HTTP Server

  ApacheConf, php-configure, php-configuration, php-conf, apacheconf

        - install
        Installs a configuration for PHP
        example: cleopatra apacheconf install

HELPDATA;
      return $help ;
    }

}