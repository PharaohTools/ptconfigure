<?php

Namespace Info;

class WireframeSketcherInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "WireframeSketcher - A great IDE from JetBrains";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "WireframeSketcher" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("wireframe-sketcher"=>"WireframeSketcher");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install Intellij, the JetBrains IDE

  WireframeSketcher, wireframe-sketcher

        - install
        Installs the latest version of Developer Tools
        example: cleopatra gittools install

HELPDATA;
      return $help ;
    }

}