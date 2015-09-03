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
          "xcode"=>"ApacheFastCGIModules" );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module is part of the Default Distribution and provides you  with a method by which you can configure Application Settings.
  You can configure default application settings

  ApacheFastCGIModules, apachefastcgimodules, apachefastcgimods, apache-fastcgi-modules

        - install
        Installs Apache Fast CGI Modules
        example: ptconfigure apache-fastcgi-modules install

HELPDATA;
      return $help ;
    }

}