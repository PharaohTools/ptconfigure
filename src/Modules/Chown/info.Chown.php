<?php

Namespace Info;

class ChownInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Chown Functionality";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Chown" => array("path", "help") );
    }

    public function routeAliases() {
      return array("chown" => "Chown");
    }

  public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command handles file user ownership changing functions.

  Chown, chown

        - path
        Will change the user ownership of a path
        example: cleopatra chown path --yes --guess --recursive --path=/a/file/path --owner=golden

HELPDATA;
      return $help ;
    }

}