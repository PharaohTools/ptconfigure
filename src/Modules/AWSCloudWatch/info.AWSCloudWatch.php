<?php

Namespace Info;

class AWSCloudWatchInfo extends Base {

  public $hidden = false;

  public $name = "The Selenium Web Browser controlling server";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "AWSCloudWatch" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("selenium-server"=>"AWSCloudWatch", "selenium"=>"AWSCloudWatch",
      "selenium-srv"=>"AWSCloudWatch", "seleniumserver"=>"AWSCloudWatch");
  }

  public function autoPilotVariables() {
    return array(
      "AWSCloudWatch" => array(
        "AWSCloudWatch" => array(
          "programDataFolder" => "/opt/AWSCloudWatch", // command and app dir name
          "programNameMachine" => "seleniumserver", // command and app dir name
          "programNameFriendly" => "Selenium Srv", // 12 chars
          "programNameInstaller" => "Selenium Server",
        ),
      )
    );
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install a few GC recommended Standard Tools
  for productivity in your system.  The kinds of tools we found ourselves
  installing on every box we have, client or server. These include curl,
  vim, drush and zip.

  AWSCloudWatch, selenium-server, selenium, selenium-srv, seleniumserver

        - install
        Installs AWSCloudWatch. Note, you'll also need Java installed
        as it is a prerequisite for Selenium
        example: cleopatra selenium install

HELPDATA;
    return $help ;
  }

}