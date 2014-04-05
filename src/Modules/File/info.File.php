<?php

Namespace Info;

class FileInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Add, Remove or Modify Files";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "File" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "File" =>  array_merge(
            array("help", "status", "create", "remove", "set-password", "exists", "show-groups", "add-to-group", "remove-from-group")
        ) );
    }

    public function routeAliases() {
      return array("file"=>"File");
    }

    public function autoPilotVariables() {
      return array(
        "File" => array(
          "File" => array(
            "programDataFolder" => "", // command and app dir name
            "programNameMachine" => "file", // command and app dir name
            "programNameFriendly" => "    File    ", // 12 chars
            "programNameInstaller" => "File",
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to modify create or modify files

  File, file

        - create
        Create a new system file, overwriting if it exists
        example: cleopatra file create --filename="somename"

        - remove
        Remove a system file
        example: cleopatra file remove --filename="somename"

        - set-password
        Set the password of a system file
        example: cleopatra file set-password --filename="somename" --new-password="somepassword"

        - exists
        Check the existence of a file
        example: cleopatra file exists --filename="somename"

        - show-groups
        Show groups to which a file belongs
        example: cleopatra file show-groups --filename="somename"

        - add-to-group
        Add file to a group
        example: cleopatra file add-to-group --filename="somename" --groupname="somegroupname"

        - remove-from-group
        Remove file from a group
        example: cleopatra file remove-from-group --filename="somename" --groupname="somegroupname"

HELPDATA;
      return $help ;
    }

}