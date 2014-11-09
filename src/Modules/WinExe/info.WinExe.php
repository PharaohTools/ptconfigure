<?php

Namespace Info;

class WinExeInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Add, Remove or Modify WinExes";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "WinExe" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "WinExe" =>  array_merge(
            array("help", "status", "pkg-install", "pkg-ensure", "pkg-remove", "update")
        ) );
    }

    public function routeAliases() {
        return array("winexe"=>"WinExe");
    }

    public function packagerName() {
        return "WinExe";
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to modify create or modify winexes

  WinExe, winexe

        - create
        Create a new system winexe, overwriting if it exists
        example: cleopatra winexe create --winexename="somename"

        - remove
        Remove a system winexe
        example: cleopatra winexe remove --winexename="somename"

        - set-password
        Set the password of a system winexe
        example: cleopatra winexe set-password --winexename="somename" --new-password="somepassword"

        - exists
        Check the existence of a winexe
        example: cleopatra winexe exists --winexename="somename"

        - show-groups
        Show groups to which a winexe belongs
        example: cleopatra winexe show-groups --winexename="somename"

        - add-to-group
        Add winexe to a group
        example: cleopatra winexe add-to-group --winexename="somename" --groupname="somegroupname"

        - remove-from-group
        Remove winexe from a group
        example: cleopatra winexe remove-from-group --winexename="somename" --groupname="somegroupname"

HELPDATA;
      return $help ;
    }

}