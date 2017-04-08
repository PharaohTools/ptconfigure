<?php

Namespace Info;

class PECLInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Add, Remove or Modify PECL Packages";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "PECL" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "PECL" =>  array_merge(
            array("help", "status", "install", "ensure", "pkg-install", "pkg-ensure", "pkg-remove", "update")
        ) );
    }

    public function routeAliases() {
        return array("pecl"=>"PECL");
    }

    public function packagerName() {
        return "PECL";
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to modify manage pecl packages

  PECL, pecl

        - pkg-install
        Install an PECL Package
        example: ptconfigure PECL pkg-install -yg --package-name="somename"

        - pkg-remove
        Remove an PECL package
        example: ptconfigure PECL pkg-remove -yg --package-name="somename"

        - exists
        Check the existence of an PECL package
        example: ptconfigure PECL pkg-exists --package-name="somename"

HELPDATA;
      return $help ;
    }

}