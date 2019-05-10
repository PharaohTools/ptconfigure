<?php

Namespace Info;

class FactsInfo extends Base {

  public $hidden = false;

  public $name = "Retrieve Facts about system";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "Facts" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("Facts"=>"Facts", "facts"=>"Facts");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This is a dummy Linux module that doesn't execute any commands.

  Facts, Facts

        - find
        Find a fact
        example: ptconfigure Facts find

        - list
        List available Facts
        example: ptconfigure Facts list

HELPDATA;
    return $help ;
  }

}