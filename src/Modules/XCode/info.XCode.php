<?php

Namespace Info;

class ApacheFastCGIModulesInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Apache Fast CGI Modules - Fast CGI modules for Apache";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "ApacheFastCGIModules" =>  array_merge(parent::routesAvailable(), array("version") ) );
    }

    public function routeAliases() {
      return array(
          "apache-fastcgi-modules"=>"ApacheFastCGIModules",
          "apachefastcgimodules"=>"ApacheFastCGIModules",
          "apachefastcgimods"=>"ApacheFastCGIModules" );
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