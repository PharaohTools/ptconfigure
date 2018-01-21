<?php

Namespace Info;

class SeleniumServerInfo extends PTConfigureBase {

  public $hidden = false;

  public $name = "The Selenium Web Browser controlling server";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "SeleniumServer" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("selenium-server"=>"SeleniumServer", "selenium"=>"SeleniumServer",
      "selenium-srv"=>"SeleniumServer", "seleniumserver"=>"SeleniumServer");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This module allows you to install Selenium Server.

  SeleniumServer, selenium-server, selenium, selenium-srv, seleniumserver

        - install
        Installs Selenium Server. Note, you'll also need Java installed
        as it is a prerequisite for Selenium
        example: ptconfigure selenium install
        example: ptconfigure selenium install --with-chrome-driver # will set the executor command to use default chrome driver


HELPDATA;
    return $help ;
  }

}