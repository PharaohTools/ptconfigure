<?php

Namespace Info;

class SystemDetectionInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "System Detection - Detect the Running Operating System";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "SystemDetection" =>  array_merge(array("detect", 'generate-defaults', "help") ) );
    }

    public function routeAliases() {
      return array("system-detection"=>"SystemDetection", "systemdetection"=>"SystemDetection");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This is a default Module and provides you with a method for accessing basic system values

  SystemDetection, system-detection, systemdetection

        - detect
        Detects the Operating System
        example: ptbuild system-detection detect

        - generate-defaults
        Generates a file of defaults for better performance
        example: ptbuild system-detection generate-defaults

HELPDATA;
      return $help ;
    }

}