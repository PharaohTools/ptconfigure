<?php

Namespace Info;

class VNCPasswdInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "VNCPasswd - Passwords for The Display Manager Solution";

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
  This module allows you to install VNCPasswd, the popular Remote/Local Desktop Manager Solution.

  VNCPasswd, vncpasswd, vnc-passwd

        - install
        Adds a VNC Password for a user
        example: ptconfigure vnc install

HELPDATA;
      return $help ;
    }

}