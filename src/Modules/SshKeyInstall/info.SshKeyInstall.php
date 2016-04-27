<?php

Namespace Info;

class SshKeyInstallInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Install SSH Public Keys to Authorized Keys for accessing a a user account";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        return array( "SshKeyInstall" =>  array_merge(
            array("help", "status", "public-key", "public", "private-key", "private")
        ) );
    }

    public function routeAliases() {
        return array("sshkeyinstall"=>"SshKeyInstall", "ssh-key-install"=>"SshKeyInstall");
    }

    public function dependencies() {
        return array("Service", "Logging", "User");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to install an SSH Public key for a user

  SshKeyInstall, sshkeyinstall, ssh-key-install

        - public-key, public
        Add an SSH Public Key to a User account
        example: ptconfigure ssh-key-install public-key
        example: ptconfigure ssh-key-install public-key -yg --public-key-data="zzzzz"
        example: ptconfigure ssh-key-install public-key -yg --public-key-file="id_rsa.pub" --user-name=dave

        - private-key, private
        Add an SSH Private Key to a User account
        example: ptconfigure ssh-key-install private-key
        example: ptconfigure ssh-key-install private-key -yg --private-key-data="zzzzz"
        example: ptconfigure ssh-key-install private-key -yg --private-key-file="/tmp/sparekeys/id_rsa" --user-name=dave --key-name=id_rsa

HELPDATA;
      return $help ;
    }

}