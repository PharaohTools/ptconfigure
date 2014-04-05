<?php

Namespace Info;

class Firefox17Info extends CleopatraBase {

    public $hidden = false;

    public $name = "Firefox 17 - A version of Firefox highly tested with Selenium Server";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Firefox17" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("ff17"=>"Firefox17", "firefox17"=>"Firefox17");
    }

    public function autoPilotVariables() {
      return array(
        "Firefox17" => array(
          "Firefox17" => array(
            "programDataFolder" => "/opt/firefox17", // command and app dir name
            "programNameMachine" => "firefox17", // command and app dir name
            "programNameFriendly" => "Firefox 17", // 12 chars
            "programNameInstaller" => "Firefox 17",
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install Firefox17.

  Firefox17, ff17, firefox17

        - install
        Installs the latest version of Firefox 17
        example: cleopatra firefox17 install

HELPDATA;
      return $help ;
    }

}