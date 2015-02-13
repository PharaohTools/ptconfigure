<?php

Namespace Info;

class MkdirInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Mkdir Functionality";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Mkdir" => array("path", "help") ) ;
    }

    public function routeAliases() {
      return array("mkdir" => "Mkdir");
    }

  public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command handles file copying functions.

  Mkdir, mkdir

        - path
        Will ask you for details for servers, then copy a file or directory from local to remote
        example: ptconfigure mkdir path
        example: ptconfigure mkdir path --yes --path="/path/to/new/directory"

HELPDATA;
      return $help ;
    }

}