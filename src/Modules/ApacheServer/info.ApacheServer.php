<?php

Namespace Info;

class ApacheServerInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Apache Server - Install or remove the Apache Server";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "ApacheServer" =>  array_merge(parent::routesAvailable(), array() ) );
    }

    public function routeAliases() {
        return array("apache-server"=>"ApacheServer", "apacheserver"=>"ApacheServer");
    }

    public function autoPilotVariables() {
      return array(
        "ApacheServer" => array(
          "ApacheServer" => array(
            "programDataFolder" => "/etc/apache2/", // command and app dir name
            "programNameMachine" => "apacheserver", // command and app dir name
            "programNameFriendly" => "Apache Serv.", // 12 chars
            "programNameInstaller" => "Apache Server",
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and provides you  with a method by which you can install Apache HTTP Server

  ApacheServer, apache-server, apacheserver

        - install
        Installs Apache HTTP Server
        example: cleopatra apacheserver install

HELPDATA;
      return $help ;
    }

}