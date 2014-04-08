<?php

Namespace Info;

class PHPCSInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "PHP Code Sniffer - The static code analysis tool";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PHPCS" =>  array_merge(parent::routesAvailable(), array("install") ) );
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