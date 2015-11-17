<?php

Namespace Info;

class XCodeInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "XCode for Apple";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "XCode" =>  array_merge(parent::routesAvailable(), array("version") ) );
    }

    public function routeAliases() {
      return array(
          "xcode"=>"XCode", "x-code"=>"XCode" );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module provides integration for XCode installation

  XCode, x-code, xcode

        - install
        Installs Apple XCode
        example: ptconfigure xcode install

HELPDATA;
      return $help ;
    }

}