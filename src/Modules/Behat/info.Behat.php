<?php

Namespace Info;

class BehatInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Behat - The PHP BDD Testing Suite";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Behat" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("behat"=>"Behat");
    }

    public function autoPilotVariables() {
      return array(
        "Behat" => array(
          "Behat" => array(
            "programNameMachine" => "behat", // command and app dir name
            "programNameFriendly" => "Behat",
            "programNameInstaller" => "Behat - Update to latest version",
            "programExecutorTargetPath" => 'behat/src/Bootstrap.php',
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to update Behat.

  Behat, behat

        - install
        Installs the latest version of behat
        example: cleopatra behat install

HELPDATA;
      return $help ;
    }

}