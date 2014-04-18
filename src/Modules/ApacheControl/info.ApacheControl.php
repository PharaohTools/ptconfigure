<?php

Namespace Info;

class ApacheControlInfo extends Base {

    public $hidden = false;

    public $name = "Apache Server Control";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "ApacheControl" => array_merge(parent::routesAvailable(), array("start", "stop", "restart", "reload") ) );
    }

    public function routeAliases() {
      return array("apachecontrol"=>"ApacheControl", "apachectl"=>"ApacheControl", "apache-control"=>"ApacheControl",
          "apache-ctl"=>"ApacheControl");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Default Modules and handles Apache Server Control Functions.

  ApacheControl, apachecontrol, apachectl

          - start
          Start the Apache server
          example: dapperstrano apachecontrol start
          example: dapperstrano apachecontrol start --yes --guess
          example: dapperstrano apachecontrol start --yes --apache-command="apache2"

          - stop
          Stop the Apache server
          example: dapperstrano apachecontrol stop
          example: dapperstrano apachecontrol stop --yes --guess
          example: dapperstrano apachecontrol stop --yes --apache-command="apache2"

          - restart
          Restart the Apache server
          example: dapperstrano apachecontrol restart
          example: dapperstrano apachecontrol restart --yes --guess
          example: dapperstrano apachecontrol restart --yes --apache-command="apache2"

          - reload
          Reloads the Apache server configuration without restarting
          example: dapperstrano apachecontrol reload
          example: dapperstrano apachecontrol reload --yes --guess
          example: dapperstrano apachecontrol reload --yes --apache-command="apache2"

HELPDATA;
      return $help ;
    }


}