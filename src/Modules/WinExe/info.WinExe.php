<?php

Namespace Info;

class WinExeInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Add, Remove or Modify Windows Executable Packages";

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
  This module allows you to {$this->name}

  WinExe, winexe

        - create
        Create a new system winexe, overwriting if it exists
        example: ptconfigure winexe create --winexename="somename"

        - remove
        Remove a system winexe
        example: ptconfigure winexe remove --winexename="somename"

        - set-password
        Set the password of a system winexe
        example: ptconfigure winexe set-password --winexename="somename" --new-password="somepassword"

        - exists
        Check the existence of a winexe
        example: ptconfigure winexe exists --winexename="somename"

        - show-groups
        Show groups to which a winexe belongs
        example: ptconfigure winexe show-groups --winexename="somename"

        - add-to-group
        Add winexe to a group
        example: ptconfigure winexe add-to-group --winexename="somename" --groupname="somegroupname"

        - remove-from-group
        Remove winexe from a group
        example: ptconfigure winexe remove-from-group --winexename="somename" --groupname="somegroupname"

HELPDATA;
      return $help ;
    }

}