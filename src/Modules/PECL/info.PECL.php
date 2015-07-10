<?php

Namespace Info;

class PECLInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Add, Remove or Modify PECLs";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "PECL" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "PECL" =>  array_merge(
            array("help", "status", "pkg-install", "pkg-ensure", "pkg-remove", "update")
        ) );
    }

    public function routeAliases() {
        return array("pecl"=>"PECL");
    }

    public function packagerName() {
        return "PECL";
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to modify create or modify pecls

  PECL, pecl

        - create
        Create a new system pecl, overwriting if it exists
        example: ptconfigure pecl create --peclname="somename"

        - remove
        Remove a system pecl
        example: ptconfigure pecl remove --peclname="somename"

        - set-password
        Set the password of a system pecl
        example: ptconfigure pecl set-password --peclname="somename" --new-password="somepassword"

        - exists
        Check the existence of a pecl
        example: ptconfigure pecl exists --peclname="somename"

        - show-groups
        Show groups to which a pecl belongs
        example: ptconfigure pecl show-groups --peclname="somename"

        - add-to-group
        Add pecl to a group
        example: ptconfigure pecl add-to-group --peclname="somename" --groupname="somegroupname"

        - remove-from-group
        Remove pecl from a group
        example: ptconfigure pecl remove-from-group --peclname="somename" --groupname="somegroupname"

HELPDATA;
      return $help ;
    }

}