<?php

Namespace Info;

class PortInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Test a Port to see if its responding";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "Port" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "Port" => array("help", "status", "is-responding") );
    }

    public function routeAliases() {
      return array("port"=>"Port");
    }

    public function autoPilotVariables() {
      return array(
        "Port" => array(
          "Port" => array(
            "programDataFolder" => "", // command and app dir name
            "programNameMachine" => "port", // command and app dir name
            "programNameFriendly" => "    Port    ", // 12 chars
            "programNameInstaller" => "Port",
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to modify create or modify ports

  Port, port

        - create
        Create a new system port, overwriting if it exists
        example: cleopatra port create --portname="somename"

        - remove
        Remove a system port
        example: cleopatra port remove --portname="somename"

        - set-password
        Set the password of a system port
        example: cleopatra port set-password --portname="somename" --new-password="somepassword"

        - exists
        Check the existence of a port
        example: cleopatra port exists --portname="somename"

        - show-groups
        Show groups to which a port belongs
        example: cleopatra port show-groups --portname="somename"

        - add-to-group
        Add port to a group
        example: cleopatra port add-to-group --portname="somename" --groupname="somegroupname"

        - remove-from-group
        Remove port from a group
        example: cleopatra port remove-from-group --portname="somename" --groupname="somegroupname"

HELPDATA;
      return $help ;
    }

}