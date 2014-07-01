<?php

Namespace Info;

class NetworkToolsInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Network Tools - Tools for working with Networks";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "NetworkTools" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("NetworkTools"=>"NetworkTools", "network-tools"=>"NetworkTools");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install a set of common Network Tools. These include
  traceroute, netstat, lsof, telnet and ps.

  NetworkTools, networktools, network-tools

        - install
        Installs the latest version of Network Tools
        example: cleopatra networktools install

HELPDATA;
      return $help ;
    }

}
