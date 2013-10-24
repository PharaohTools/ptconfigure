<?php

Namespace Info;

class ApacheControlInfo extends Base {

    public $hidden = false;

    public $name = "Apache Server Control";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "ApacheControl" => array_merge(parent::routesAvailable(), array("start", "stop", "restart") ) );
    }

    public function routeAliases() {
      return array("apachecontrol"=>"ApacheControl", "apachectl"=>"ApacheControl", "apache-control"=>"ApacheControl",
          "apache-ctl"=>"ApacheControl");
    }

    public function autoPilotVariables() {
      return array(
        "ApacheControl" => array(
          "apacheControlRestartExecute" => array(
            "apacheControlRestartExecute" => "boolean",
            "apacheControlRestartApacheCommand" => "string", ) ,
          "apacheControlStartExecute" => array(
            "apacheControlStartExecute" => "boolean",
            "apacheControlStartApacheCommand" => "string", ) ,
          "apacheControlStopExecute" => array(
            "apacheControlStopExecute" => "boolean",
            "apacheControlStopApacheCommand" => "string", ) ,
        ) ,
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and handles Apache Server Control Functions.

  ApacheControl, apachecontrol, apachectl

          - start
          Start the Apache server
          example: dapperstrano apachecontrol start

          - stop
          Stop the Apache server
          example: dapperstrano apachecontrol stop

          - restart
          Restart the Apache server
          example: dapperstrano apachecontrol restart

          - reload
          Reloads the Apache server configuration without restarting
          example: dapperstrano apachecontrol reload

HELPDATA;
      return $help ;
    }


}