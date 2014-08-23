<?php

Namespace Info;

class PHPCIInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "PHPCI - The PHP Build Server";

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
        Installs PHPCI through git, with its dependencies
        example: cleopatra phpci install

        - config-default, default-config
        Installs a default config.yml file for PHPCI
        example: cleopatra phpci config-default --yes --guess

        - install-default-database
        Installs a default database and user for PHPCI
        example: cleopatra phpci install-default-database --yes --guess
            --mysql-admin-user="root" # guess will provide root
            --mysql-admin-pass="some-pass" # guess will provide cleopatra

  You can install a complete local version of PHPCI with the following:

  sudo cleopatra phpci install --yes --guess
  sudo cleopatra phpci install-default-database --yes --guess
  sudo cleopatra phpci config-default --yes --guess

HELPDATA;
      return $help ;
    }

}