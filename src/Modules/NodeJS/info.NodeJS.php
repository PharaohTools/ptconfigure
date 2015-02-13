<?php

Namespace Info;

class NodeJSInfo extends PTConfigureBase {

  public $hidden = false;

  public $name = "Node JS - The Server Side Javascript Engine";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "NodeJS" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("node-js"=>"NodeJS", "nodejs"=>"NodeJS");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install Node JS, The Server Side JS Language

  NodeJS, node-js, nodejs

        - install
        Installs NodeJS through apt-get.
        example: ptconfigure node-js install

HELPDATA;
    return $help ;
  }

}