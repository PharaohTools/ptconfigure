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