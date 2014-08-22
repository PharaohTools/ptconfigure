<?php

Namespace Info;

class ComposerInfo extends ComposerBase {

    public $hidden = false;

    public $name = "Composer - Upgrade or Re-install Composer";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Composer" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("cleo"=>"Composer", "cleopatra"=>"Composer");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to update Composer.

  Composer, cleo, cleopatra

        - install
        Installs the latest version of cleopatra
        example: cleopatra cleopatra install

HELPDATA;
      return $help ;
    }

}