<?php

Namespace Info;

class LighttpdControlInfo extends Base {

    public $hidden = false;

    public $name = "Lighttpd Server Control";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "LighttpdControl" => array_merge(parent::routesAvailable(), array("start", "stop", "restart") ) );
    }

    public function routeAliases() {
      return array("lighttpdcontrol"=>"LighttpdControl", "lighttpdctl"=>"LighttpdControl", "lighttpd-control"=>"LighttpdControl",
          "lighttpd-ctl"=>"LighttpdControl");
    }

    public function autoPilotVariables() {
      return array(
        "LighttpdControl" => array(
          "lighttpdControlRestartExecute" => array(
            "lighttpdControlRestartExecute" => "boolean",
            "lighttpdControlRestartLighttpdCommand" => "string", ) ,
          "lighttpdControlStartExecute" => array(
            "lighttpdControlStartExecute" => "boolean",
            "lighttpdControlStartLighttpdCommand" => "string", ) ,
          "lighttpdControlStopExecute" => array(
            "lighttpdControlStopExecute" => "boolean",
            "lighttpdControlStopLighttpdCommand" => "string", ) ,
        ) ,
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and handles Lighttpd Server Control Functions.

  LighttpdControl, lighttpdcontrol, lighttpdctl

          - start
          Start the Lighttpd server
          example: dapperstrano lighttpdcontrol start

          - stop
          Stop the Lighttpd server
          example: dapperstrano lighttpdcontrol stop

          - restart
          Restart the Lighttpd server
          example: dapperstrano lighttpdcontrol restart

          - reload
          Reloads the Lighttpd server configuration without restarting
          example: dapperstrano lighttpdcontrol reload

HELPDATA;
      return $help ;
    }


}