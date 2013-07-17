<?php

Namespace Info;

class IntelliJInfo extends Base {

    public $hidden = false;

    public $name = "IntelliJ";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "IntelliJ" =>  array_merge(parent::defaultActionsAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("intellij"=>"IntelliJ");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install Intellij, the JetBrains IDE

  IntelliJ, intellij

        - install
        Installs the latest version of Developer Tools
        example: cleopatra gittools install

HELPDATA;
      return $help ;
    }

}