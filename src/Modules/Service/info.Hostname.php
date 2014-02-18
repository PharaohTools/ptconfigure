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
        return array( "Service" => array("help", "status", "change", "show") );
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

        - change
        Change the system service
        example: cleopatra service change --service="my-laptop"

        - show
        Show the system service
        example: cleopatra service show

HELPDATA;
      return $help ;
    }

}