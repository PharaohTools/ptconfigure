<?php

Namespace Info;

class PTConfigureInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "PTConfigure - Upgrade or Re-install PTConfigure";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PTConfigure" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("cleo"=>"PTConfigure", "ptconfigure"=>"PTConfigure");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to update PTConfigure.

  PTConfigure, cleo, ptconfigure

        - install
        Installs the latest version of ptconfigure
        example: ptconfigure ptconfigure install

HELPDATA;
      return $help ;
    }

}