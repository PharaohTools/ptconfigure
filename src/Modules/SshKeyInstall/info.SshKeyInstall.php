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
        Add an SSH Public Key to an account
        example: ptconfigure ssh-key-install public-key
        example: ptconfigure ssh-key-install public-key --yes --public-key-data="zzzzz"
        example: ptconfigure ssh-key-install public-key --yes --public-key-file="id_rsa.pub" --user-name=dave

        - private-key, private
        Add an SSH Public Key to an account
        example: ptconfigure ssh-key-install public-key
        example: ptconfigure ssh-key-install public-key --yes --public-key-data="zzzzz"
        example: ptconfigure ssh-key-install public-key --yes --public-key-file="id_rsa.pub" --user-name=dave

HELPDATA;
      return $help ;
    }

}