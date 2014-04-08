<?php

Namespace Info;

class PhlagrantInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Phlagrant - The Virtual Machine management solution for PHP";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Phlagrant" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("phlagrant"=>"Phlagrant");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install or update Phlagrant.

  Phlagrant, phlagrant

        - install
        Installs the latest version of phlagrant
        example: cleopatra phlagrant install

HELPDATA;
      return $help ;
    }

}