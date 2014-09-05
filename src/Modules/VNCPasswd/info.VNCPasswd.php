<?php

Namespace Info;

class VNCPasswdInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "VNCPasswd - The Display Manager Solution";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "VNCPasswd" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("vncpasswd"=>"VNCPasswd", "vnc-passwd"=>"VNCPasswd");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install VNCPasswd, the popular Remote/Local Desktop Manager Solution.

  VNCPasswd, vncpasswd, vnc-passwd

        - install
        Installs VNCPasswd through a package manager
        example: cleopatra vnc install

HELPDATA;
      return $help ;
    }

}