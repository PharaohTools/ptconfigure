<?php

Namespace Info;

class SudoNoPassInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Configure Passwordless Sudo for any User";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "SudoNoPass" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("sudonopass"=>"SudoNoPass", "sudo-nopass"=>"SudoNoPass",
        "sudo-passwordless"=>"SudoNoPass");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to add an entry to the system sudo file that will
  allow your user to have passwordless sudo. This is required for
  quite a few of the  builds provided by Golden Contact, as
  will perform test execution, software installs and more, silently.

  SudoNoPass, sudonopass, sudo-nopass, sudo-passwordless

        - install
        Installs the sudo without password entry
        example: cleopatra sudo-nopass install

HELPDATA;
      return $help ;
    }

}