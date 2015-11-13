<?php

Namespace Info;

class PharaohEnterpriseInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "PharaohEnterprise - Upgrade or Re-install Pharaoh Enterprise Version";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PharaohEnterprise" =>  array_merge(parent::routesAvailable(), array("install", "test-credentials", "test-creds",
      "save-credentials", "save-creds") ) );
    }

    public function routeAliases() {
      return array("pharaoh-enterprise"=>"PharaohEnterprise", "pharaohenterprise"=>"PharaohEnterprise");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to install Pharaoh Tools Enterprise editions.

  PharaohEnterprise, pharaoh-enterprise, pharaohenterprise

        - install
        Installs the latest version of Pharaoh Tools Enterprise Edition
        example: ptconfigure pharaoh-enterprise install -yg
                     --user=example@mail.com
                     --key=ABCDEFGHIJKLMNOPQRSTUVWXYZ012345 # API Key

        - save-credentials, save-creds
        Save credentials for authenticating with Pharaoh Enterprise Servers silently later
        example: ptconfigure pharaoh-enterprise save-creds -yg
                     --user=example@mail.com
                     --key=ABCDEFGHIJKLMNOPQRSTUVWXYZ012345 # API Key

        - test-credentials, test-creds
        Test authenticate your credentials with a Pharaoh Enterprise Server
        example: ptconfigure pharaoh-enterprise test-creds -yg
                     --user=phpengine
                     --key=ABCDEFGHIJKLMNOPQRSTUVWXYZ012345 # API Key

HELPDATA;
      return $help ;
    }

}