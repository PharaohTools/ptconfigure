<?php

Namespace Info;

class XvfbInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Xvfb - The Display Manager Solution";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Xvfb" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("vnc"=>"Xvfb");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install Xvfb, the popular Virtual Machine Solution.

  Xvfb, vnc

        - install
        Installs Xvfb through apt-get
        example: cleopatra vnc install

HELPDATA;
      return $help ;
    }

}