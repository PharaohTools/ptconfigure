<?php

Namespace Info;

class ApacheConfInfo extends Base {

    public $hidden = false;

    public $name = "Apache Conf - Install a Apache Configuration";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "ApacheConf" =>  array("install", "help") );
    }

    public function routeAliases() {
        return array("apache-configuration"=>"ApacheConf", "apache-configure"=>"ApacheConf", "apache-conf"=>"ApacheConf",
            "apacheconf"=>"ApacheConf");
    }

    // @todo structure of the exposedParams method
    public function exposedParams() {
        return array("install" => array(
                        "template_*" => "", )
            );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module lets you install a configuration for Apache HTTP Server. The only commands available are this help
  and install.

  ApacheConf, apache-configure, apache-configuration, apache-conf, apacheconf

        - install
        Installs a configuration for Apache
        example: cleopatra apacheconf install

HELPDATA;
      return $help ;
    }

}