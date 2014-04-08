<?php

Namespace Info;

class GemInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Ruby Gems Package Manager";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "Gem" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "Gem" =>  array_merge(
            array("help", "status", "create", "remove", "set-password", "exists", "show-groups", "add-to-group", "remove-from-group")
        ) );
    }

    public function routeAliases() {
        return array("gem"=>"Gem");
    }

    public function packagerName() {
        return "Gem";
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to modify create or modify gems

  Gem, gem

        - create
        Create a new system gem, overwriting if it exists
        example: cleopatra gem create --gemname="somename"

        - remove
        Remove a system gem
        example: cleopatra gem remove --gemname="somename"

        - set-password
        Set the password of a system gem
        example: cleopatra gem set-password --gemname="somename" --new-password="somepassword"

        - exists
        Check the existence of a gem
        example: cleopatra gem exists --gemname="somename"

        - show-groups
        Show groups to which a gem belongs
        example: cleopatra gem show-groups --gemname="somename"

        - add-to-group
        Add gem to a group
        example: cleopatra gem add-to-group --gemname="somename" --groupname="somegroupname"

        - remove-from-group
        Remove gem from a group
        example: cleopatra gem remove-from-group --gemname="somename" --groupname="somegroupname"

HELPDATA;
      return $help ;
    }

}