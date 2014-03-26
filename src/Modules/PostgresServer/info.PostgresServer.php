<?php

Namespace Info;

class PostgresServerInfo extends Base {

  public $hidden = false;

  public $name = "Postgres Server - The Postgres RDBMS Server";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "PostgresServer" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("postgres-server"=>"PostgresServer", "postgresserver"=>"PostgresServer");
  }

  public function autoPilotVariables() {
    return array(
      "PostgresServer" => array(
        "PostgresServer" => array(
          "programDataFolder" => "/opt/PostgresServer", // command and app dir name
          "programNameMachine" => "postgresserver", // command and app dir name
          "programNameFriendly" => "Postgres Server!", // 12 chars
          "programNameInstaller" => "Postgres Server",
        ),
      )
    );
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install the Postgres Server. Currently only
  Postgres Workbench, the Database management GUI provided by Oracle for
  Postgres.

  PostgresServer, postgres-server, postgresserver

        - install
        Install some Postgres Server Tools through apt-get.
        example: cleopatra postgres-server install

  Notes, during postgres install a root password will be set. First, it'll look
  for the parameter --postgres-root-pass, if this is not set, it'll look in the
  cleopatra config for a postgres-default-root-pass setting, and failing both of
  those it will just set the password for root to cleopatra.

HELPDATA;
    return $help ;
  }

}