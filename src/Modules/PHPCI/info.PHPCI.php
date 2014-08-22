<?php

Namespace Info;

class PHPCIInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "PHPCI - The Java Build Server";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PHPCI" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("phpci"=>"PHPCI");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install PHPCI, the popular Build Server.

  PHPCI, phpci

        - install
        Installs PHPCI through apt-get
        example: cleopatra phpci install

HELPDATA;
      return $help ;
    }

}