<?php

Namespace Info;

class InstallPackageInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Cleopatra Predefined Installers";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "InstallPackage" =>  array_merge(parent::routesAvailable(), array("autopilot", "dev-client",
        "devclient", "dev-server", "devserver", "dev-server-slim", "devserverslim", "dev-server-slim-nosudo",
        "devserverslimnosudo", "git-server", "gitserver",
        "jenkins-server",  "jenkinsserver", "build-server", "buildserver", "production", "prod-server", "prod",
        "test-server","testserver") ) );
    }

    public function routeAliases() {
      return array("installpackage"=>"InstallPackage", "installpack"=>"InstallPackage", "install-pack"=>"InstallPackage",
        "install"=>"InstallPackage", "inpack"=>"InstallPackage", "install-package"=>"InstallPackage");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and provides you  with a method by which you can perform some default CLI Installs of
  different types of box.

  InstallPackage, installpackage, installpack, install-pack, install, inpack, install-package

    - dev-client
      install a dev client machine for you to work on, a bunch of IDE's, DB's and a complete set of the
      tools you need to start work immediately.
      example: cleopatra install autopilot dev-client

    - dev-server
      Install the preconfigured list of software for a developers server.
      example: cleopatra install autopilot dev-server

    - test-server
      Install the preconfigured list of software for a testing server.
      example: cleopatra install autopilot test-server

    - build-server
      Install the preconfigured list of software for a build server.
      example: cleopatra install autopilot test-server

    - production
      Install the preconfigured list of software for a production server.
      example: cleopatra install autopilot test-server

HELPDATA;
      return $help ;
    }

}