<?php

Namespace Info;

class SshKeyStoreInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "For Storing and Accessing SSH Keys";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        return array( "SshKeyStore" =>  array_merge(
            array("help", "find")
        ) );
    }

    public function routeAliases() {
        return array("sshkeystore"=>"SshKeyStore", "ssh-key-store"=>"SshKeyStore");
    }

    public function dependencies() {
        return array("Service", "Logging");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to find credentials for a key on a machine

  SshKeyStore, sshkeystore, ssh-key-store

        - find
        Add an SSH Public Key to an account
        example: ptconfigure ssh-key-store find --key=daveylad
        example: ptconfigure ssh-key-store find --key=daveylad --prefer=user

HELPDATA;
      return $help ;
    }

}