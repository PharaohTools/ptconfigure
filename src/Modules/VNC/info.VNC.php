<?php

Namespace Info;

class VNCInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "VNC - The local Virtual Machine Solution";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "VNC" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("virtualbox"=>"VNC");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install VNC, the popular Virtual Machine Solution.

  VNC, virtualbox

        - install
        Installs VNC through apt-get
        example: cleopatra virtualbox install

HELPDATA;
      return $help ;
    }

}