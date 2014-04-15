<?php

Namespace Info;

class FirefoxInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Firefox - Install or remove Firefox";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Firefox" =>  array_merge(parent::routesAvailable(), array() ) );
    }

    public function routeAliases() {
        return array("firefox"=>"Firefox");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and provides you  with a method by which you can install Firefox from your package
  manager

  Firefox, firefox

        - install
        Installs Firefox
        example: cleopatra firefox install

HELPDATA;
      return $help ;
    }

}