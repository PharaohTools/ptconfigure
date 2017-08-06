<?php

Namespace Info;

class JavaInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Java JDK";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Java" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("java"=>"Java");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to install Java JDK 1.7 or 1.8 .

  Java, java

        - install
        Installs a version of Oracle Java JDK 1.7 or 1.8. It will also configure java,
        javac and javaws to be provided by the new Oracle version.
        example: ptconfigure java install # will install 1.7
        example: ptconfigure java install --java-install-version=1.7
        example: ptconfigure java install --java-install-version=1.8


HELPDATA;
      return $help ;
    }

}