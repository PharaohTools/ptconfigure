<?php

Namespace Info;

class PTArtefactsInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "PTArtefacts - Upgrade or Re-install PTArtefacts";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PTArtefacts" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("ptartefacts"=>"PTArtefacts");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to update PTArtefacts.

  PTArtefacts, ptartefacts

        - install
        Installs the latest version of ptartefacts
        example: ptconfigure ptartefacts install -yg # will install no web interfaces for cli only use
        example: ptconfigure ptartefacts install -yg --with-webfaces # will guess artefacts.pharaoh.tld at 127.0.0.1
        example: ptconfigure ptartefacts install -yg --with-webfaces --vhe-url=artefacts.site.com --vhe-ip-port=1.2.3.4:80
        example: ptconfigure ptartefacts install -yg --version=latest # will keep your settings files while upgrading code to latest
        example: ptconfigure ptartefacts install -yg --enable-ssh # Enable Inbound Connections via SSH (SFTP/SCP)
        example: ptconfigure ptartefacts install -yg --enable-http # Enable Inbound Connections via HTTP


HELPDATA;
      return $help ;
    }

}