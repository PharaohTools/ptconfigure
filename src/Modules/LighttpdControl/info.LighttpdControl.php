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

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Default Modules and handles Lighttpd Server Control Functions.

  LighttpdControl, lighttpdcontrol, lighttpdctl

          - start
          Start the Lighttpd server
          example: ptdeploy lighttpdcontrol start
          example: ptdeploy lighttpdcontrol start --yes

          - stop
          Stop the Lighttpd server
          example: ptdeploy lighttpdcontrol stop
          example: ptdeploy lighttpdcontrol stop --yes

          - restart
          Restart the Lighttpd server
          example: ptdeploy lighttpdcontrol restart
          example: ptdeploy lighttpdcontrol restart --yes

          - reload
          Reloads the Lighttpd server configuration without restarting
          example: ptdeploy lighttpdcontrol reload
          example: ptdeploy lighttpdcontrol reload --yes

HELPDATA;
      return $help ;
    }


}