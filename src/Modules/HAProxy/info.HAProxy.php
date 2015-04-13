<?php

Namespace Info;

class HAProxyInfo extends PTConfigureBase {

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
        example: ptconfigure haproxy install

        - configure, config
        Configure Load Balancing with HA Proxy Server
        example: ptconfigure haproxy configure
                    --with-stats # if this flag is included, include the haproxy stats
                    --environment-name="my-nodes" # your environment containing nodes
                    --template_target_port #
                    --listen_ip_port="" # ip and port to listen to

HELPDATA;
      return $help ;
    }

}