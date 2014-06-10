<?php

Namespace Info;

class HAProxyInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "HA Proxy Server - Install or remove the HA Proxy Server";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "HAProxy" =>  array_merge(parent::routesAvailable(), array("config", "configure", "install") ) );
    }

    public function routeAliases() {
        return array("ha-proxy"=>"HAProxy", "haproxy"=>"HAProxy");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module provides installs HA Proxy Server

  HAProxy, ha-proxy, haproxy

        - install
        Installs HA Proxy Server
        example: cleopatra haproxy install

        - configure
        Configure Load Balancing with HA Proxy Server
        example: cleopatra haproxy configure

HELPDATA;
      return $help ;
    }

}