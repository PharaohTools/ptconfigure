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
        return array( "User" =>  array_merge(
            array("help", "status", "create", "remove", "set-password", "exists", "show-groups", "add-to-group", "remove-from-group")
        ) );
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
  This command allows you to modify create or modify users

  User, user

        - create
        Create a new system user, overwriting if it exists
        example: cleopatra user create --username="somename"

        - remove
        Remove a system user
        example: cleopatra user remove --username="somename"

        - set-password
        Set the password of a system user
        example: cleopatra user set-password --username="somename" --new-password="somepassword"

        - exists
        Check the existence of a user
        example: cleopatra user exists --username="somename"

        - show-groups
        Show groups to which a user belongs
        example: cleopatra user show-groups --username="somename"

        - add-to-group
        Add user to a group
        example: cleopatra user add-to-group --username="somename" --groupname="somegroupname"

        - remove-from-group
        Remove user from a group
        example: cleopatra user remove-from-group --username="somename" --groupname="somegroupname"

HELPDATA;
      return $help ;
    }

}