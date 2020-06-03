<?php

Namespace Info;

class PTBuildInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "PTBuild - Upgrade or Re-install PTBuild";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PTBuild" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("ptbuild"=>"PTBuild");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to update PTBuild.

  PTBuild, ptbuild

        - install
        Installs the latest version of ptbuild
        
        example: ptconfigure ptbuild install -yg --with-webfaces --vhe-url=build.example.com --vhe-ip-port=0.0.0.0:80 --enable-ssl

        example: ptconfigure ptbuild install -yg # will install no web interfaces for cli only use
        example: ptconfigure ptbuild install -yg --with-webfaces # will guess build.pharaoh.tld at 127.0.0.1
        example: ptconfigure ptbuild install -yg --with-webfaces --vhe-url=build.site.com --vhe-ip-port=1.2.3.4:80
        example: ptconfigure ptbuild install -yg --version=latest # will keep your settings files while upgrading code to latest


HELPDATA;
      return $help ;
    }

}