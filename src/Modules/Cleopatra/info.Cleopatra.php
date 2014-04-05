<?php

Namespace Info;

class CleopatraInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Cleopatra - Upgrade or Re-install Cleopatra";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Cleopatra" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("cleo"=>"Cleopatra", "cleopatra"=>"Cleopatra");
    }

    public function autoPilotVariables() {
      return array(
        "Cleopatra" => array(
          "Cleopatra" => array(
            "programNameMachine" => "cleopatra", // command and app dir name
            "programNameFriendly" => " Cleopatra! ",
            "programNameInstaller" => "Cleopatra - Update to latest version",
            "programExecutorTargetPath" => 'cleopatra/src/Bootstrap.php',
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to update Cleopatra.

  Cleopatra, cleo, cleopatra

        - install
        Installs the latest version of cleopatra
        example: cleopatra cleopatra install

HELPDATA;
      return $help ;
    }

}