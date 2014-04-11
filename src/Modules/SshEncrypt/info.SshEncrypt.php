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

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install admin users for MySQL so that MySQL can
  be managed without using the Root User.

  SshEncrypt, ssh-encrypt, sshencrypt

        - install
        Installs Mysql Admin Users.
        example: cleopatra mysql-admins install

HELPDATA;
    return $help ;
  }

}