<?php

Namespace Info;

class PTTrackInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Upgrade or Re-install PTTrack";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PTTrack" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("pttrack"=>"PTTrack");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to update PTTrack.

  PTTrack, pttrack

        - install
        Installs the latest version of pttrack
        
        example: ptconfigure pttrack install -yg --version=latest --vhe-url=track.example.com --vhe-ip-port=0.0.0.0:80 --enable-ssl
        
        example: ptconfigure pttrack install -yg --with-webfaces

HELPDATA;
      return $help ;
    }

}