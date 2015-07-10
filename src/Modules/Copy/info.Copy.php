<?php

Namespace Info;

class CopyInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Copy Functionality";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Copy" => array_merge(parent::routesAvailable(), array("put") ) );
    }

    public function routeAliases() {
      return array("copy" => "Copy");
    }

  public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module handles file copying functions.

  Copy, copy

        - put
        Will copy a filr or directory from one location to another
        example: ptconfigure copy put
        example: ptconfigure copy put --yes --source="/tmp/file" --target="/home/user/file"

HELPDATA;
      return $help ;
    }

}