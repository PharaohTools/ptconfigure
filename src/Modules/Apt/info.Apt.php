<?php

Namespace Info;

class AptInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Add, Remove or Modify Apts";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "Apt" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "Apt" =>  array_merge(
            array("help", "status", "pkg-install", "pkg-ensure", "pkg-remove", "update")
        ) );
    }

    public function routeAliases() {
        return array("apt"=>"Apt");
    }

    public function packagerName() {
        return "Apt";
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to handle apt packages

  Apt, apt

        - pkg-install
        Install an Apt Package
        example: ptconfigure apt pkg-install -yg --package-name="somename"

        - pkg-remove
        Remove an Apt package
        example: ptconfigure apt pkg-remove -yg --package-name="somename"

        - exists
        Check the existence of an apt package
        example: ptconfigure apt pkg-exists --package-name="somename"
        
        - remove
        Remove a system apt
        example: ptconfigure apt remove --aptname="somename"

HELPDATA;
      return $help ;
    }

}