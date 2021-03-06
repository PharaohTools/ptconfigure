<?php

Namespace Info;

class HostnameInfo extends PTConfigureBase {

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

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to view or modify hostname

  Hostname, hostname

        - change
        Change the system hostname
        example: ptconfigure hostname change --hostname="my-laptop"

        - show
        Show the system hostname
        example: ptconfigure hostname show

HELPDATA;
      return $help ;
    }

}