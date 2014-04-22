<?php

Namespace Info;

class SshKeyInstallInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Apply security functions to the SSH accounts/setup of the machine";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "SshKeyInstall" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "SshKeyInstall" =>  array_merge(
            array("help", "status", "securify")
        ) );
    }

    public function routeAliases() {
        return array("sshharden"=>"SshKeyInstall", "ssh-harden"=>"SshKeyInstall");
    }

    public function dependencies() {
        return array("Service");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to modify create or modify sshhardens

  SshKeyInstall, sshharden, ssh-harden

        - securify
        Add some security to your SSH accounts
        example: cleopatra ssh-harden securify

HELPDATA;
      return $help ;
    }

}