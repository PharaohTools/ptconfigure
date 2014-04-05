<?php

Namespace Info;

class ParallaxInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Parallax - The parallel execution tool from Golden Contact";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Parallax" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("parallax"=>"Parallax");
    }

    public function autoPilotVariables() {
      return array(
        "Parallax" => array(
          "Parallax" => array(
            "programNameMachine" => "parallax", // command and app dir name
            "programNameFriendly" => " Parallax! ",
            "programNameInstaller" => "Parallax - Update to latest version",
            "programExecutorTargetPath" => 'parallax/src/Bootstrap.php',
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to update Parallax.

  Parallax, parallax

        - install
        Installs the latest version of parallax
        example: cleopatra parallax install

HELPDATA;
      return $help ;
    }

}