<?php

Namespace Info;

class BakeryInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Bakery - Create OS Images for Multiple Platforms";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Bakery" =>  array_merge(parent::routesAvailable(), array("osinstall", 'bake') ) );
    }

    public function routeAliases() {
      return array("bakery"=>"Bakery");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to create OS Images for multiple platforms. Create Base images installed from Operating System
  ISO Files or Configured Images using Pharaoh Configure DSL Scripts too.

  Bakery, bakery

        - osinstall
        Installs an Operating System from its base ISO Image, into a format of your choice
        example: ptconfigure bakery osinstall # will install 1.7
        example: ptconfigure bakery osinstall --bakery-install-version=1.7
        example: ptconfigure bakery osinstall --bakery-install-version=1.8

        - bake
        Create a new Image, including specified configurations, into format of your choice
        example: ptconfigure bakery bake # will install 1.7
        example: ptconfigure bakery bake --bakery-install-version=1.7
        example: ptconfigure bakery bake --bakery-install-version=1.8


HELPDATA;
      return $help ;
    }

}