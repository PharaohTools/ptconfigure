<?php

Namespace Info;

class PHPAPCInfo extends CleopatraBase {

  public $hidden = false;

  public $name = "PHP APC - Commonly used PHP APC";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "PHPAPC" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("php-apc"=>"PHPAPC", "phpapc"=>"PHPAPC");
  }

  public function autoPilotVariables() {
    return array(
      "PHPAPC" => array(
        "PHPAPC" => array(
          "programDataFolder" => "/opt/PHPAPC", // command and app dir name
          "programNameMachine" => "phpapc", // command and app dir name
          "programNameFriendly" => "PHP APC!", // 12 chars
          "programNameInstaller" => "PHP APC",
        ),
      )
    );
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install some common and helpful PHP APC.

  PHPAPC, php-apc, phpapc, phpapc

        - install
        Install PHP APC.
        example: cleopatra phpapc install

HELPDATA;
    return $help ;
  }

}