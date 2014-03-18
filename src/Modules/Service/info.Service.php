<?php

Namespace Info;

class ServiceInfo extends Base {

    public $hidden = false;

    public $name = "View or Modify Service";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "Service" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "Service" => array("help", "status", "start", "stop", "restart", "ensure-running", "run-at-reboots") );
    }

    public function routeAliases() {
      return array("service"=>"Service");
    }

    public function autoPilotVariables() {
      return array(
        "Service" => array(
          "Service" => array(
            "programDataFolder" => "", // command and app dir name
            "programNameMachine" => "service", // command and app dir name
            "programNameFriendly" => "    Service    ", // 12 chars
            "programNameInstaller" => "Service",
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to view or modify service

  Service, service

        - start
        Start a system service
        example: cleopatra service start --service-name="apache2"

        - stop
        Stop a system service
        example: cleopatra service restart --service-name="apache2"

        - restart
        Restart a system service
        example: cleopatra service restart --service-name="apache2"

        - ensure-running
        Ensure a system service is running. If it is already running, dont attempt to start it
        If it is not running, start it
        example: cleopatra service ensure-running --service-name="apache2"

        - run-at-reboots
        Ensure a system service will auto start on reboots.
        example: cleopatra service run-at-reboots --service-name="apache2"

HELPDATA;
      return $help ;
    }

}