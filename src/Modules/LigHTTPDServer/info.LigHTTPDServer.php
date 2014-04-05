<?php

Namespace Info;

class LigHTTPDServerInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "LigHTTPD Server - Install or remove the LigHTTPD Server";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "LigHTTPDServer" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("lighttpd-server"=>"LigHTTPDServer", "lighttpdserver"=>"LigHTTPDServer");
    }

    public function autoPilotVariables() {
      return array(
        "LigHTTPDServer" => array(
          "LigHTTPDServer" => array(
            "programDataFolder" => "/opt/lighttpd/", // command and app dir name
            "programNameMachine" => "lighttpdserver", // command and app dir name
            "programNameFriendly" => "LigHTTPD Serv.", // 12 chars
            "programNameInstaller" => "LigHTTPD Server",
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and provides you  with a method by which you can configure Application Settings.
  You can configure default application settings, ie: mysql admin user, host, pass

  LigHTTPDServer, lighttpd-server, lighttpdserver

        - install
        Installs LigHTTPD HTTP Server
        example: cleopatra lighttpd-server install

HELPDATA;
      return $help ;
    }

}