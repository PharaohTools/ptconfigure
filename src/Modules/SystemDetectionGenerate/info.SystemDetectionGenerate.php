<?php

Namespace Info;

class SystemDetectionGenerateInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "System Detection - Detect the Running Operating System";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "SystemDetectionGenerate" =>  array_merge(array("detect", 'gen', 'generate-defaults', "help") ) );
    }

    public function routeAliases() {
      return array("system-detection-generate"=>"SystemDetectionGenerate", "systemdetectiongenerate"=>"SystemDetectionGenerate");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This is a default Module and provides you with a method for accessing basic system values

  SystemDetectionGenerate, system-detection-generate, systemdetectiongenerate

        - gen, generate-defaults
        Generates a file of defaults for better performance
        example: ptbuild system-detection-generate generate-defaults

HELPDATA;
      return $help ;
    }

}