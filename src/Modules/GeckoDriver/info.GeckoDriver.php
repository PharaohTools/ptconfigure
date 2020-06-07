<?php

Namespace Info;

class GeckoDriverInfo extends PTConfigureBase {

  public $hidden = false;

  public $name = "The Gecko Browser remote controlling server";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "GeckoDriver" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("gecko-driver"=>"GeckoDriver", "geckodriver"=>"GeckoDriver",
      "geckodriver-server"=>"GeckoDriver", "geckodriverserver"=>"GeckoDriver");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This module allows you to install a few GC recommended Standard Tools
  for productivity in your system.  The kinds of tools we found ourselves
  installing on every box we have, client or server. These include curl,
  vim, drush and zip.

  GeckoDriver, geckodriver-server, geckodriver, geckodriver-srv, geckodriverserver

        - install
        Installs GeckoDriver. Note, you'll also need Java installed
        as it is a prerequisite for GeckoDriver
        example: ptconfigure geckodriver install

HELPDATA;
    return $help ;
  }

}