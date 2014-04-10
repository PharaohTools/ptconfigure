<?php

Namespace Info;

class BehatInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Behat - The PHP BDD Testing Suite";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Behat" =>  parent::routesAvailable() );
    }

    public function routeAliases() {
      return array("behat"=>"Behat");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install Behat.

  Behat, behat

        - install
        Installs the latest version of behat
        example: cleopatra behat install

HELPDATA;
      return $help ;
    }

}