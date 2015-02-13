<?php

Namespace Info;

class WireframeSketcherInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Wireframe Sketcher - the Wireframing application";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "WireframeSketcher" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("wireframe-sketcher"=>"WireframeSketcher", "wireframesketcher"=>"WireframeSketcher");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install Wireframe Sketcher, for Wireframing

  WireframeSketcher, wireframe-sketcher, wireframesketcher

        - install
        Installs the latest version of Developer Tools
        example: ptconfigure wireframe-sketcher install

HELPDATA;
      return $help ;
    }

}