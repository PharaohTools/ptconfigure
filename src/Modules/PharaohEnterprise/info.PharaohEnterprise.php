<?php

Namespace Info;

class PharaohEnterpriseInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "PharaohEnterprise - Upgrade or Re-install Pharaoh Enterprise Version";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PharaohEnterprise" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("pharaoh-enterprise"=>"PharaohEnterprise", "pharaohenterprise"=>"PharaohEnterprise");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to install Pharaoh Tools Enterprise editions.

  PharaohEnterprise, pharaoh-enterprise, pharaohenterprise

        - install
        Installs the latest version of PharaohEnterprise
        example: ptconfigure pharaoh-enterprise install -yg
                     --user=phpengine
                     --key=ABCDEFGHIJKLMNOPQRSTUVWXYZ012345 # API Key

HELPDATA;
      return $help ;
    }

}