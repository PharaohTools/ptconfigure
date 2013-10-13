<?php

Namespace Info;

class GCInstallCustomPackageInfo extends Base {

    public $hidden = false;

    public $name = "Cleopatra Predefined Installers - Customised for Demos";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "GCInstallCustomPackage" =>  array_merge(parent::routesAvailable(), array(
        "production-server-slim", "prod-server-slim", "dev-server-slim" ) ) );
    }

    public function routeAliases() {
      return array("install-pack-custom"=>"GCInstallCustomPackage", "install-package-custom"=>"GCInstallCustomPackage");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This is an Extension which provides you  with a method by which you can perform some default CLI Installs of
  different types of box.

  install-pack-custom, install-package-custom

    - dev-server-slim
      Install the preconfigured list of software for a slim dev server.
      example: cleopatra install-pack-custom dev-server-slim

    - production-server-slim
      Install the preconfigured list of software for a slim production server.
      example: cleopatra install-pack-custom production-server-slim

HELPDATA;
      return $help ;
    }

}