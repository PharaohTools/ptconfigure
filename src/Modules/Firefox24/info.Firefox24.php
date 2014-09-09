<?php

Namespace Info;

class Firefox24Info extends CleopatraBase {

    public $hidden = false;

    public $name = "Firefox 24 - A version of Firefox highly tested with Selenium Server";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Firefox24" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("ff24"=>"Firefox24", "firefox24"=>"Firefox24");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install Firefox24.

  Firefox24, ff24, firefox24

        - install
        Installs the latest version of Firefox 24
        example: cleopatra firefox24 install

HELPDATA;
      return $help ;
    }

}