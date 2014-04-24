<?php

Namespace Info;

class SshEncryptInfo extends CleopatraBase {

  public $hidden = false;

  public $name = "Mysql Admins - Install administrative users for Mysql";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "SshEncrypt" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("ssh-encrypt"=>"SshEncrypt", "sshencrypt"=>"SshEncrypt");
  }

  public function dependencies() {
      return array("Logging", "Encryption") ;
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install an encrypted private SSH key or to encrypt one.

  SshEncrypt, ssh-encrypt, sshencrypt

        - install
        Installs an encrypted SSH Key.
        example: cleopatra mysql-admins install

HELPDATA;
    return $help ;
  }

}