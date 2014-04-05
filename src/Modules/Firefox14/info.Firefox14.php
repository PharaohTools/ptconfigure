<?php

Namespace Info;

class Firefox14Info extends CleopatraBase {

    public $hidden = false;

    public $name = "Firefox 14 - A version of Firefox highly tested with Selenium Server";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Firefox14" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("ff14"=>"Firefox14", "firefox14"=>"Firefox14");
    }

    public function autoPilotVariables() {
      return array(
        "Firefox14" => array(
          "Firefox14" => array(
            "programDataFolder" => "/opt/firefox14", // command and app dir name
            "programNameMachine" => "firefox14", // command and app dir name
            "programNameFriendly" => "Firefox 14", // 12 chars
            "programNameInstaller" => "Firefox 14",
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install Firefox14.

  Firefox14, ff14, firefox14

        - install
        Installs the latest version of Firefox 14
        example: cleopatra firefox14 install

HELPDATA;
      return $help ;
    }

}