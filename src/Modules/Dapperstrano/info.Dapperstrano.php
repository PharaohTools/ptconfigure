<?php

Namespace Info;

class DapperstranoInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Dapperstrano - The PHP Automated Website Deployment tool";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Dapperstrano" =>  parent::routesAvailable() );
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

        - ensure
        Installs the latest version of dapperstrano, only if a version is not installed
        example: cleopatra dapperstrano ensure

HELPDATA;
      return $help ;
    }

}