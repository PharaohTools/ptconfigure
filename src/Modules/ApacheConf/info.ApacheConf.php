<?php

Namespace Info;

class ApacheConfInfo extends Base {

    public $hidden = false;

    public $name = "Apache Conf - Install a Apache Configuration";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "ApacheConf" =>  array_merge(parent::routesAvailable(), array() ) );
    }

    public function routeAliases() {
        return array("apache-configuration"=>"ApacheConf", "apache-configure"=>"ApacheConf", "apache-conf"=>"ApacheConf",
            "apacheconf"=>"ApacheConf");
    }

    public function autoPilotVariables() {
      return array(
        "ApacheConf" => array(
          "ApacheConf" => array(
            "programDataFolder" => "/etc/apacheconf/", // command and app dir name
            "programNameMachine" => "apacheconf", // command and app dir name
            "programNameFriendly" => "Apache Conf.", // 12 chars
            "programNameInstaller" => "Apache Conf",
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and provides you  with a method by which you can install Apache HTTP Server

  ApacheConf, apache-configure, apache-configuration, apache-conf, apacheconf

        - install
        Installs a configuration for Apache
        example: cleopatra apacheconf install

HELPDATA;
      return $help ;
    }

}