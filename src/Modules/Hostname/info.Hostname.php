<?php

Namespace Info;

class HostnameInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "View or Modify Hostname";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "Hostname" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "Hostname" => array("help", "status", "change", "show") );
    }

    public function routeAliases() {
      return array("hostname"=>"Hostname");
    }

    public function autoPilotVariables() {
      return array(
        "Hostname" => array(
          "Hostname" => array(
            "programDataFolder" => "", // command and app dir name
            "programNameMachine" => "hostname", // command and app dir name
            "programNameFriendly" => "    Hostname    ", // 12 chars
            "programNameInstaller" => "Hostname",
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to view or modify hostname

  Hostname, hostname

        - change
        Change the system hostname
        example: cleopatra hostname change --hostname="my-laptop"

        - show
        Show the system hostname
        example: cleopatra hostname show

HELPDATA;
      return $help ;
    }

}