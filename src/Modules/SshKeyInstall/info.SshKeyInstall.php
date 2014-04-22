<?php

Namespace Info;

class SshKeyInstallInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Install SSH Public Keys to a user account";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        return array( "SshKeyInstall" =>  array_merge(
            array("help", "status", "securify")
        ) );
    }

    public function routeAliases() {
        return array("sshkeyinstall"=>"SshKeyInstall", "ssh-key-install"=>"SshKeyInstall");
    }

    public function dependencies() {
        return array("Service", "Console");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install an SSH Public key for a user

  SshKeyInstall, sshkeyinstall, ssh-key-install

        - public-key
        Add an SSH Public Key to an account
        example: cleopatra ssh-key-install public-key
        example: cleopatra ssh-key-install public-key --yes --public-key-data="zzzzz"
        example: cleopatra ssh-key-install public-key --yes --public-key-file="id_rsa.pub" --user-name=dave

HELPDATA;
      return $help ;
    }

}