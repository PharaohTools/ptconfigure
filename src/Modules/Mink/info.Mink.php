<?php

Namespace Info;

class MinkInfo extends Base {

    public $hidden = false;

    public $name = "Mink - The PHP BDD Testing Suite";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Mink" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("mink"=>"Mink");
    }

    public function autoPilotVariables() {
      return array(
        "Mink" => array(
          "Mink" => array(
            "programNameMachine" => "mink", // command and app dir name
            "programNameFriendly" => "Mink",
            "programNameInstaller" => "Mink - Update to latest version",
            "programExecutorTargetPath" => 'mink/src/Bootstrap.php',
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to update Mink.

  Mink, mink

        - install
        Installs the latest version of mink
        example: cleopatra mink install

HELPDATA;
      return $help ;
    }

}