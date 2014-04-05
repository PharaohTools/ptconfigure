<?php

Namespace Info;

class SshKeygenInfo extends CleopatraBase {

  public $hidden = false;

  public $name = "SSH Keygen - Generate SSH Kay Pairs";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "SshKeygen" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("sshkeygen"=>"SshKeygen", "ssh-keygen"=>"SshKeygen");
  }

  public function autoPilotVariables() {
    return array(
      "SshKeygen" => array(
        "SshKeygen" => array(
          "programDataFolder" => "/opt/SshKeygen", // command and app dir name
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
  This command allows you to install an SSH Key Pair.

  SshKeygen, ssh-keygen, sshkeygen

        - install
        Installs a new SSH Key
        example: cleopatra ssh-keygen install

        - uninstall
        Removes an SSH Key
        example: cleopatra ssh-keygen uninstall

HELPDATA;
    return $help ;
  }

}