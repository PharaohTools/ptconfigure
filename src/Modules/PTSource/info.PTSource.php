<?php

Namespace Info;

class PTSourceInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "PTSource - Upgrade or Re-install PTSource";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PTSource" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("ptsource"=>"PTSource");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to update PTSource.

  PTSource, ptsource

        - install
        Installs the latest version of ptsource
        
        example: ptconfigure ptsource install -yg --with-webfaces --vhe-url=track.example.com --vhe-ip-port=0.0.0.0:80 --enable-ssl --enable-ssh --enable-http --enable-git
        
        example: ptconfigure ptsource install -yg # will install no web interfaces for cli only use
        example: ptconfigure ptsource install -yg --with-webfaces # will guess source.pharaoh.tld at 127.0.0.1
        example: ptconfigure ptsource install -yg --with-webfaces --vhe-url=source.site.com --vhe-ip-port=1.2.3.4:80
        example: ptconfigure ptsource install -yg --version=latest # will keep your settings files while upgrading code to latest


HELPDATA;
      return $help ;
    }

}