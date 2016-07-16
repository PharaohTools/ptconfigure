<?php

Namespace Info;

class PTWebApplicationInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "PTWebApplication - Upgrade or Re-install PTWebApplication";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PTWebApplication" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("ptwebapplication"=>"PTWebApplication");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to update PTWebApplication.

  PTWebApplication, ptwebapplication

        - install
        Installs the latest version of ptwebapplication
        example: ptconfigure ptwebapplication install -yg # will install no web interfacesm for cli only use
        example: ptconfigure ptwebapplication install -yg --with-webfaces # will guess build.pharaoh.tld at 127.0.0.1
        example: ptconfigure ptwebapplication install -yg --with-webfaces --vhe-url=build.site.com --vhe-ip-port=1.2.3.4:80
        example: ptconfigure ptwebapplication install -yg --version=latest # will keep your settings files while upgrading code to latest


HELPDATA;
      return $help ;
    }

}