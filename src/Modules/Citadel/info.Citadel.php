<?php

Namespace Info;

class CitadelInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Citadel Server - Install or remove the Citadel Server";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Citadel" =>  array_merge(parent::routesAvailable(), array("config", "configure", "install") ) );
    }

    public function routeAliases() {
        return array("citadel-server"=>"Citadel", "citadel"=>"Citadel");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module installs Citadel Server and provides configuration

  Citadel, citadel-server, citadel

        - install
        Installs Citadel Server
        example: ptconfigure citadel install

        - configure
        Configure E-Mail with Citadel Server
        example: ptconfigure citadel configure

HELPDATA;
      return $help ;
    }

}