<?php

Namespace Info;

class PHPCIInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "PHPCI - The Java Build Server";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PHPCI" =>  array_merge(parent::routesAvailable(), array("install", "config-default", "default-config",
        "install-default-database") ) );
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

        - config-default, default-config
        Installs PHPCI through apt-get
        example: cleopatra phpci config-default --yes --guess

        - install-default-database
        Installs PHPCI through apt-get
        example: cleopatra phpci install-default-database --yes --guess
            --mysql-admin-user="root" # guess will provide root
            --mysql-admin-pass="some-pass" # guess will provide cleopatra

HELPDATA;
      return $help ;
    }

}