<?php

Namespace Info;

class PHPSSHInfo extends CleopatraBase {

  public $hidden = false;

  public $name = "PHP SSH - PHP SSH Extension";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "PHPSSH" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("php-ssh"=>"PHPSSH", "phpssh"=>"PHPSSH");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install the PHP SSH default Module

  PHPSSH, php-ssh, phpssh

        - install
        Installs the PECL PHP SSH Extension.
        example: cleopatra phpssh install

HELPDATA;
    return $help ;
  }

}