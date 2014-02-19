<?php

Namespace Info;

class PearInfo extends Base {

    public $hidden = false;

    public $name = "Add, Remove or Modify Pears";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "Pear" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "Pear" =>  array_merge(
            array("help", "status", "create", "remove", "set-password", "exists", "show-groups", "add-to-group", "remove-from-group")
        ) );
    }

    public function routeAliases() {
        return array("pear"=>"Pear");
    }

    public function packagerName() {
        return "Pear";
    }

    public function autoPilotVariables() {
      return array(
        "Pear" => array(
          "Pear" => array(
            "programDataFolder" => "", // command and app dir name
            "programNameMachine" => "pear", // command and app dir name
            "programNameFriendly" => "    Pear    ", // 12 chars
            "programNameInstaller" => "Pear",
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to modify create or modify pears

  Pear, pear

        - create
        Create a new system pear, overwriting if it exists
        example: cleopatra pear create --pearname="somename"

        - remove
        Remove a system pear
        example: cleopatra pear remove --pearname="somename"

        - set-password
        Set the password of a system pear
        example: cleopatra pear set-password --pearname="somename" --new-password="somepassword"

        - exists
        Check the existence of a pear
        example: cleopatra pear exists --pearname="somename"

        - show-groups
        Show groups to which a pear belongs
        example: cleopatra pear show-groups --pearname="somename"

        - add-to-group
        Add pear to a group
        example: cleopatra pear add-to-group --pearname="somename" --groupname="somegroupname"

        - remove-from-group
        Remove pear from a group
        example: cleopatra pear remove-from-group --pearname="somename" --groupname="somegroupname"

HELPDATA;
      return $help ;
    }

}