<?php

Namespace Info;

class NagiosServerInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Nagios Server - Install or remove the Nagios Server";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "NagiosServer" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("nagios-server"=>"NagiosServer", "nagiosserver"=>"NagiosServer", "nagios"=>"NagiosServer");
    }

    public function autoPilotVariables() {
      return array(
        "NagiosServer" => array(
          "NagiosServer" => array(
            "programDataFolder" => "/opt/nagios/", // command and app dir name
            "programNameMachine" => "nagiosserver", // command and app dir name
            "programNameFriendly" => "Nagios Serv.", // 12 chars
            "programNameInstaller" => "Nagios Server",
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and provides you with a method by which you can install Nagios.

  NagiosServer, nagios-server, nagiosserver, nagios

        - install
        Installs Nagios Network Monitoring Server
        example: cleopatra nagios-server install

HELPDATA;
      return $help ;
    }

}