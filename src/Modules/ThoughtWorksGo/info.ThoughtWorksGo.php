<?php

Namespace Info;

class ThoughtWorksGoInfo extends CleopatraBase {

  public $hidden = false;

  public $name = "The Continuous Delivery server from ThoughtWorks";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "ThoughtWorksGo" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("mysql-server-galera"=>"ThoughtWorksGo", "mysqlservergalera"=>"ThoughtWorksGo");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install the ThoughtWorks Go.

  ThoughtWorksGo, thoughtworks-go, thoughtworksgo

        - install
        Install the the Thoughtworks Go Server and/or Agent
        example: cleopatra thoughtworksgo install --yes --guess --install-server --install-agent

HELPDATA;
    return $help ;
  }

}