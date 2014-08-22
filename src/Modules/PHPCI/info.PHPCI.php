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
      return array("jenkins"=>"PHPCI");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install PHPCI, the popular Build Server.

  PHPCI, jenkins

        - install
        Installs PHPCI through apt-get
        example: cleopatra jenkins install

HELPDATA;
      return $help ;
    }

}