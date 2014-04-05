<?php

Namespace Info;

class NginxServerInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Nginx Server - Install or remove the Nginx Server";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "NginxServer" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("nginx-server"=>"NginxServer", "nginxserver"=>"NginxServer");
    }

    public function autoPilotVariables() {
      return array(
        "NginxServer" => array(
          "NginxServer" => array(
            "programDataFolder" => "/opt/nginx/", // command and app dir name
            "programNameMachine" => "nginxserver", // command and app dir name
            "programNameFriendly" => "Nginx Serv.", // 12 chars
            "programNameInstaller" => "Nginx Server",
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and provides you  with a method by which you can configure Application Settings.
  You can configure default application settings, ie: mysql admin user, host, pass

  NginxServer, nginx-server, nginxserver

        - install
        Installs Nginx HTTP Server
        example: cleopatra nginx-server install

HELPDATA;
      return $help ;
    }

}