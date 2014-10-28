<?php

Namespace Info;

class HHVMInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "HHVM - The PHP Virtual Machine runtime from Facebook";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "HHVM" =>  array_merge(parent::routesAvailable(), array() ) );
    }

    public function routeAliases() {
      return array("hhvm"=>"HHVM");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install HHVM, the popular Build Server.

  HHVM, hhvm

        - install
        Installs HHVM through package manager
        example: cleopatra hhvm install

        - uninstall
        Uninstalls HHVM through package manager
        example: cleopatra hhvm uninstall

HELPDATA;
      return $help ;
    }

}