<?php

Namespace Info;

class PHPStormInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "PHPStorm - A great IDE from JetBrains";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PHPStorm" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("phpstorm"=>"PHPStorm");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install Intellij, the JetBrains IDE

  PHPStorm, phpstorm

        - install
        Installs the latest version of Developer Tools
        example: cleopatra gittools install

HELPDATA;
      return $help ;
    }

}