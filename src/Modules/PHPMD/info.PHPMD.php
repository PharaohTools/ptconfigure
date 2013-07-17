<?php

Namespace Info;

class PHPMDInfo extends Base {

    public $hidden = false;

    public $name = "PHPMD";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PHPMD" =>  array_merge(parent::defaultActionsAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("phpmd"=>"PHPMD", "phpmd"=>"PHPMD", "php-md"=>"PHPMD");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install PHPMD from a GC Repo.

  PHPMD

        - install
        Installs the latest GC Repo version of PHPMD
        example: cleopatra phpmd install

HELPDATA;
      return $help ;
    }

}