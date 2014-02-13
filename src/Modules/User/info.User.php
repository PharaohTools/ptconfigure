<?php

Namespace Info;

class UserInfo extends Base {

    public $hidden = false;

    public $name = "Add, Remove or Modify Users";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "User" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "User" =>  array_merge( array("help", "status", "add", "remove") ) );
    }

    public function routeAliases() {
      return array("user"=>"User");
    }

    public function autoPilotVariables() {
      return array(
        "User" => array(
          "User" => array(
            "programDataFolder" => "", // command and app dir name
            "programNameMachine" => "user", // command and app dir name
            "programNameFriendly" => "    User    ", // 12 chars
            "programNameInstaller" => "User",
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install the latest available User in the Ubuntu
  repositories.

  User, user

        - add
        Add a new system user
        example: cleopatra user add

        - remove
        Reove a system user
        example: cleopatra user remove

HELPDATA;
      return $help ;
    }

}