<?php

Namespace Info;

class SaharaInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "View or Modify Sahara";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "Sahara" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "Sahara" => array("help", 'mode-on', 'mode-off') );
    }

    public function routeAliases() {
      return array("sahara"=>"Sahara");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to perform actions for Sahara Web Services

  Sahara, sahara

        - mode-on
        Change the Mode of this server to send requests from other providers into Sahara instead
        example: ptconfigure sahara mode-on --provider="aws" --sahara="api.saharawebservices.com"
        example: ptconfigure sahara mode-on --provider="digitalocean" --sahara="api.saharawebservices.com"
        example: ptconfigure sahara mode-on --provider="rackspace" --sahara="api.saharawebservices.com"

        - mode-off
        Remove Changes to the Mode of this server, stop forcing requests to other providers into Sahara
        example: ptconfigure sahara mode-off --provider="aws"
        example: ptconfigure sahara mode-off --provider="digitalocean"
        example: ptconfigure sahara mode-off --provider="rackspace"


HELPDATA;
      return $help ;
    }

}