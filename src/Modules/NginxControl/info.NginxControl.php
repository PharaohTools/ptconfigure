<?php

Namespace Info;

class NginxControlInfo extends Base {

    public $hidden = false;

    public $name = "Nginx Server Control";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "NginxControl" => array_merge(parent::routesAvailable(), array("start", "stop", "restart") ) );
    }

    public function routeAliases() {
      return array("nginxcontrol"=>"NginxControl", "nginxctl"=>"NginxControl", "nginx-control"=>"NginxControl",
          "nginx-ctl"=>"NginxControl");
    }

    public function autoPilotVariables() {
      return array(
        "NginxControl" => array(
          "nginxControlRestartExecute" => array(
            "nginxControlRestartExecute" => "boolean",
            "nginxControlRestartNginxCommand" => "string", ) ,
          "nginxControlStartExecute" => array(
            "nginxControlStartExecute" => "boolean",
            "nginxControlStartNginxCommand" => "string", ) ,
          "nginxControlStopExecute" => array(
            "nginxControlStopExecute" => "boolean",
            "nginxControlStopNginxCommand" => "string", ) ,
        ) ,
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Default Modules and handles Nginx Server Control Functions.

  NginxControl, nginxcontrol, nginxctl

          - start
          Start the Nginx server
          example: dapperstrano nginxcontrol start

          - stop
          Stop the Nginx server
          example: dapperstrano nginxcontrol stop

          - restart
          Restart the Nginx server
          example: dapperstrano nginxcontrol restart

          - reload
          Reloads the Nginx server configuration without restarting
          example: dapperstrano nginxcontrol reload

HELPDATA;
      return $help ;
    }


}