<?php

Namespace Info;

class GeneratorInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Generator Functionality";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Generator" => array_merge(array("help", "put") ) );
    }

    public function routeAliases() {
      return array("copy" => "Generator");
    }

  public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module handles file copying functions.

  Generator, copy

        - put
        Will copy a filr or directory from one location to another
        example: ptconfigure copy put
        example: ptconfigure copy put --yes --source="/tmp/file" --target="/home/user/file"

HELPDATA;
      return $help ;
    }

}