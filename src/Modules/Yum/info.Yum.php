<?php

Namespace Info;

class YumInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Add, Remove or Modify Yum Packages";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        return array( "Yum" => array("help", "status", "pkg-install", "pkg-ensure", "pkg-remove", "update") );
    }

    public function routeAliases() {
        return array("yum"=>"Yum");
    }

    public function packagerName() {
        return "Yum";
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to modify create or modify yums

  Yum, yum

        - create
        Create a new system yum, overwriting if it exists
        example: ptconfigure yum create --yumname="somename"

        - remove
        Remove a system yum
        example: ptconfigure yum remove --yumname="somename"

        - set-password
        Set the password of a system yum
        example: ptconfigure yum set-password --yumname="somename" --new-password="somepassword"

        - exists
        Check the existence of a yum
        example: ptconfigure yum exists --yumname="somename"

        - show-groups
        Show groups to which a yum belongs
        example: ptconfigure yum show-groups --yumname="somename"

        - add-to-group
        Add yum to a group
        example: ptconfigure yum add-to-group --yumname="somename" --groupname="somegroupname"

        - remove-from-group
        Remove yum from a group
        example: ptconfigure yum remove-from-group --yumname="somename" --groupname="somegroupname"

HELPDATA;
      return $help ;
    }

}