<?php

Namespace Info;

class ServiceInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Start, Stop or Restart a Service";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "Service" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "Service" => array("help", "status", "start", "stop", "restart", "ensure-running", "is-running", "run-at-reboots") );
    }

    public function routeAliases() {
      return array("service"=>"Service");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to view or modify service

  Service, service

        - start
        Start a system service
        example: ptconfigure service start --service-name="apache2"

        - stop
        Stop a system service
        example: ptconfigure service restart --service-name="apache2"

        - restart
        Restart a system service
        example: ptconfigure service restart --service-name="apache2"

        - ensure-running
        Ensure a system service is running. If it is already running, dont attempt to start it
        If it is not running, start it
        example: ptconfigure service ensure-running --service-name="apache2"

        - is-running
        Checks whether a system service is running.
        example: ptconfigure service is-running --service-name="apache2"

        - run-at-reboots
        Ensure a system service will auto start on reboots.
        example: ptconfigure service run-at-reboots --service-name="apache2"

HELPDATA;
      return $help ;
    }

}