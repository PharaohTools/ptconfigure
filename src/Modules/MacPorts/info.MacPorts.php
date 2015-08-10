<?php

Namespace Info;

class AptInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Add, Remove or Modify Apts";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "Apt" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "Apt" =>  array_merge(
            array("help", "status", "pkg-install", "pkg-ensure", "pkg-remove", "update")
        ) );
    }

    public function routeAliases() {
        return array("apt"=>"Apt");
    }

    public function packagerName() {
        return "Apt";
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to modify create or modify apts

  Apt, apt

        - create
        Create a new system apt, overwriting if it exists
        example: ptconfigure apt create --aptname="somename"

        - remove
        Remove a system apt
        example: ptconfigure apt remove --aptname="somename"

        - set-password
        Set the password of a system apt
        example: ptconfigure apt set-password --aptname="somename" --new-password="somepassword"

        - exists
        Check the existence of a apt
        example: ptconfigure apt exists --aptname="somename"

        - show-groups
        Show groups to which a apt belongs
        example: ptconfigure apt show-groups --aptname="somename"

        - add-to-group
        Add apt to a group
        example: ptconfigure apt add-to-group --aptname="somename" --groupname="somegroupname"

        - remove-from-group
        Remove apt from a group
        example: ptconfigure apt remove-from-group --aptname="somename" --groupname="somegroupname"

HELPDATA;
      return $help ;
    }

}