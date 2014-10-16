<?php

Namespace Info;

class ChromeDriverInfo extends CleopatraBase {

  public $hidden = false;

  public $name = "The Selenium Web Browser controlling server";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "ChromeDriver" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("selenium-server"=>"ChromeDriver", "selenium"=>"ChromeDriver",
      "selenium-srv"=>"ChromeDriver", "seleniumserver"=>"ChromeDriver");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install a few GC recommended Standard Tools
  for productivity in your system.  The kinds of tools we found ourselves
  installing on every box we have, client or server. These include curl,
  vim, drush and zip.

  ChromeDriver, selenium-server, selenium, selenium-srv, seleniumserver

        - install
        Installs ChromeDriver. Note, you'll also need Java installed
        as it is a prerequisite for Selenium
        example: cleopatra selenium install

HELPDATA;
    return $help ;
  }

}