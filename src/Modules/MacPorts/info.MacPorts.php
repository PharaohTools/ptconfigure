<?php

Namespace Info;

class MacPortsInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Add, Remove or Modify MacPortss";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "MacPorts" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "MacPorts" =>  array_merge(
            array("help", "status", "pkg-install", "pkg-ensure", "pkg-remove", "update", "install", "ensure")
        ) );
    }

    public function routeAliases() {
        return array("macPorts"=>"MacPorts", "macports"=>"MacPorts", "mac-ports"=>"MacPorts");
    }

    public function packagerName() {
        return "MacPorts";
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to use MacPorts Package Manager on OSX

  MacPorts, macPorts

        - create
        Create a new system macPorts, overwriting if it exists
        example: ptconfigure macPorts create --macPortsname="somename"

        - remove
        Remove a system macPorts
        example: ptconfigure macPorts remove --macPortsname="somename"

        - exists
        Check the existence of a macPorts
        example: ptconfigure macPorts exists --macPortsname="somename"

        - show-groups
        Show groups to which a macPorts belongs
        example: ptconfigure macPorts show-groups --macPortsname="somename"

        - add-to-group
        Add macPorts to a group
        example: ptconfigure macPorts add-to-group --macPortsname="somename" --groupname="somegroupname"

        - remove-from-group
        Remove macPorts from a group
        example: ptconfigure macPorts remove-from-group --macPortsname="somename" --groupname="somegroupname"

HELPDATA;
      return $help ;
    }

}