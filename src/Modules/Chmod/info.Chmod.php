<?php

Namespace Info;

class ChmodInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Chmod Functionality";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Chmod" => array("path", "help") );
    }

    public function routeAliases() {
      return array("chmod" => "Chmod");
    }

  public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command handles file permission functions.

  Chmod, chmod

        - path
        Will change the file permission mode of a path
        example: cleopatra chmod path --yes --guess --recursive --path=/a/file/path --mode=0777


HELPDATA;
      return $help ;
    }

}