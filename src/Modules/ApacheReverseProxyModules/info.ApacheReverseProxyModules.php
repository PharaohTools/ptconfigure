<?php

Namespace Info;

class ApacheReverseProxyModulesInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Apache Reverse Proxy Modules - Reverse Proxy/Load Balancer Modules for Apache";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "ApacheReverseProxyModules" =>  array_merge(parent::routesAvailable(), array("version") ) );
    }

    public function routeAliases() {
      return array("apache-reverse-proxy-modules"=>"ApacheReverseProxyModules", "apache-proxy-mods"=>"ApacheReverseProxyModules", "apacheproxymodules"=>"ApacheReverseProxyModules",
          "apache-lb-mods"=>"ApacheReverseProxyModules", "apache-load-balancer-modules"=>"ApacheReverseProxyModules");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and provides you  with a method by which you can configure Application Settings.
  You can configure default application settings, ie: mysql admin user, host, pass

  ApacheReverseProxyModules, apache-reverse-proxy-modules, apache-proxy-mods, apacheproxymodules, apache-lb-mods,
  apache-load-balancer-modules

        - install
        Installs Load Balancer/Reverse Proxy Apache Modules
        example: cleopatra apache-lb-mods install

HELPDATA;
      return $help ;
    }

}