<?php

Namespace Info;

class DapperstranoInfo extends Base {

    public $hidden = false;

    public $name = "Dapperstrano";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Dapperstrano" =>  array_merge(parent::defaultActionsAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("dapper"=>"Dapperstrano", "dapperstrano"=>"Dapperstrano");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to update Dapperstrano.

  Dapperstrano, dapper, dapperstrano

        - install
        Installs the latest version of dapperstrano
        example: cleopatra dapperstrano install

HELPDATA;
      return $help ;
    }

}