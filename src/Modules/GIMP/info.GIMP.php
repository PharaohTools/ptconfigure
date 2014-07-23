<?php

Namespace Info;

class GIMPInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "GIMP - The Java Build Server";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "GIMP" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("jenkins"=>"GIMP");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install GIMP, the popular Build Server.

  GIMP, jenkins

        - install
        Installs GIMP through apt-get
        example: cleopatra jenkins install

HELPDATA;
      return $help ;
    }

}