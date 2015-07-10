<?php

Namespace Info;

class PHPAPCInfo extends PTConfigureBase {

  public $hidden = false;

  public $name = "PHP APC - Commonly used PHP APC";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "PHPAPC" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("php-apc"=>"PHPAPC", "phpapc"=>"PHPAPC");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This module allows you to install some common and helpful PHP APC.

  PHPAPC, php-apc, phpapc, phpapc

        - install
        Install PHP APC.
        example: ptconfigure phpapc install

HELPDATA;
    return $help ;
  }

}