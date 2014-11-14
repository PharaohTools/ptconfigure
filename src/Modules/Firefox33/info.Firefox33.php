<?php

Namespace Info;

class Firefox33Info extends CleopatraBase {

    public $hidden = false;

    public $name = "Firefox 33 - A version of Firefox highly tested with Selenium Server";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Firefox33" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("ff33"=>"Firefox33", "firefox33"=>"Firefox33");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install Firefox33.

  Firefox33, ff33, firefox33

        - install
        Installs the latest version of Firefox 33
        example: cleopatra firefox33 install

HELPDATA;
      return $help ;
    }

}