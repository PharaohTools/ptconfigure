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

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Default Modules and handles Nginx Server Control Functions.

  NginxControl, nginxcontrol, nginxctl

          - start
          Start the Nginx server
          example: ptdeploy nginxcontrol start

          - stop
          Stop the Nginx server
          example: ptdeploy nginxcontrol stop

          - restart
          Restart the Nginx server
          example: ptdeploy nginxcontrol restart

          - reload
          Reloads the Nginx server configuration without restarting
          example: ptdeploy nginxcontrol reload

HELPDATA;
      return $help ;
    }


}