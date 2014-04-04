<?php

Namespace Info;

class SshEncryptInfo extends Base {

  public $hidden = false;

  public $name = "Mysql Admins - Install administrative users for Mysql";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "SshEncrypt" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("mysql-admins"=>"SshEncrypt", "mysqladmins"=>"SshEncrypt");
  }

  public function autoPilotVariables() {
    return array(
      "SshEncrypt" => array(
        "SshEncrypt" => array(
          "programDataFolder" => "/opt/SshEncrypt", // command and app dir name
          "programNameMachine" => "mysqladmins", // command and app dir name
          "programNameFriendly" => "Mysql Admins!", // 12 chars
          "programNameInstaller" => "Mysql Admins",
          "mysqlNewAdminUser" => "string",
          "mysqlNewAdminPass" => "string",
          "mysqlRootUser" => "string",
          "mysqlRootPass" => "string",
          "dbHost" => "string"
        ),
      )
    );
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install admin users for MySQL so that MySQL can
  be managed without using the Root User.

  SshEncrypt, mysql-admins, mysqladmins

        - install
        Installs Mysql Admin Users.
        example: cleopatra mysql-admins install

HELPDATA;
    return $help ;
  }

}