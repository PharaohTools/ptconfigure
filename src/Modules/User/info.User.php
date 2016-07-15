<?php

Namespace Info;

class UserInfo extends PTConfigureBase {

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

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to modify create or modify users

  User, user

        - create
        Create a new system user
        example: ptconfigure user create --username="somename"

        - remove
        Remove a system user
        example: ptconfigure user remove --username="somename"

        - ensure-exists
        Check the existence of a user, and if they don't exist, create them
        example: ptconfigure user ensure-exists --username="somename"

        - exists
        Check the existence of a user
        example: ptconfigure user exists --username="somename"

        - set-password
        Set the password of a system user
        example: ptconfigure user set-password --username="somename" --new-password="somepassword"

        - show-groups
        Show groups to which a user belongs
        example: ptconfigure user show-groups --username="somename"

        - add-to-group
        Add user to a group
        example: ptconfigure user add-to-group --username="somename" --groupname="somegroupname"

        - remove-from-group
        Remove user from a group
        example: ptconfigure user remove-from-group --username="somename" --groupname="somegroupname"

HELPDATA;
      return $help ;
    }

}