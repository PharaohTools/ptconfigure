<?php

Namespace Info;

class DapperstranoInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Dapperstrano - The PHP Automated Website Deployment tool";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Dapperstrano" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("dapper"=>"Dapperstrano", "dapperstrano"=>"Dapperstrano");
    }

    public function autoPilotVariables() {
      return array(
        "Dapperstrano" => array(
          "Dapperstrano" => array(
            "programNameMachine" => "dapperstrano", // command and app dir name
            "programNameFriendly" => "Dapperstrano",
            "programNameInstaller" => "Dapperstrano - Update to latest version",
            "programExecutorTargetPath" => 'dapperstrano/src/Bootstrap.php',
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to update Dapperstrano.

  Dapperstrano, dapper, dapperstrano

        - install
        Installs the latest version of dapperstrano
        example: cleopatra dapperstrano install

HELPDATA;
      return $help ;
    }

}