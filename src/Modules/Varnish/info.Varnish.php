<?php

Namespace Info;

class VarnishInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "The HTTP Cache";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Varnish" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("varnish"=>"Varnish");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install Varnish, the popular HTTP Cache

  Varnish, varnish

        - install
        Installs Varnish through apt-get
        example: ptconfigure varnish install

HELPDATA;
      return $help ;
    }

}