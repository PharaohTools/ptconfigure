<?php

Namespace Info;

class MkdirInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Mkdir Functionality";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Mkdir" => array("dir", "help") ) ;
    }

    public function routeAliases() {
      return array("mkdir" => "Mkdir");
    }

  public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command handles file copying functions.

  Mkdir, mkdir

        - dir
        Will ask you for details for servers, then copy a file or directory from local to remote
        example: cleopatra mkdir dir
        example: cleopatra mkdir dir --yes --path="/path/to/new/directory"

HELPDATA;
      return $help ;
    }

}