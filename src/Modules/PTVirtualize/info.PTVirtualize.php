<?php

Namespace Info;

class PTVirtualizeInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "PTVirtualize - The Virtual Machine management solution for PHP";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PTVirtualize" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("ptvirtualize"=>"PTVirtualize");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install or update PTVirtualize.

  PTVirtualize, ptvirtualize

        - install
        Installs the latest version of ptvirtualize
        example: ptconfigure ptvirtualize install

HELPDATA;
      return $help ;
    }

}