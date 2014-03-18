<?php

Namespace Info;

class StateDetectionInfo extends Base {

    public $hidden = false;

    public $name = "State Detection - Detect the State of an Application";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "StateDetection" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("state-detection"=>"StateDetection", "statedetection"=>"StateDetection");
    }

    public function autoPilotVariables() {
      return array(
        "StateDetection" => array(
          "StateDetection" => array(
            "programDataFolder" => "/opt/statedetection/", // command and app dir name
            "programNameMachine" => "statedetection", // command and app dir name
            "programNameFriendly" => "State Detect", // 12 chars
            "programNameInstaller" => "State Detection",
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This is a default Module and provides you with a method by which you can configure Application Settings.
  You can configure default application settings, ie: mysql admin user, host, pass

  StateDetection, state-detection, statedetection

        - detect
        Detects the State of an Application
        example: cleopatra state-detection detect apache

HELPDATA;
      return $help ;
    }

}