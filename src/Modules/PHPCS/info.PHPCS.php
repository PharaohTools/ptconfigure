<?php

Namespace Info;

class PHPCSInfo extends Base {

    public $hidden = false;

    public $name = "PHPCS";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PHPCS" =>  array_merge(parent::defaultActionsAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("phpcs"=>"PHPCS");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install PHPCS from a GC Repo.

  PHPCS

        - install
        Installs the latest version of PHPCS
        example: cleopatra phpcs install

HELPDATA;
      return $help ;
    }

}