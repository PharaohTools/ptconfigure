<?php

Namespace Info;

class FileInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Functions to Modify Files";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "File" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "File" =>  array_merge( array("help", "create", "delete", "exists", "append", "should-have-line") ) );
    }

    public function routeAliases() {
      return array("file"=>"File");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to modify files or check their existence

  File, file

        - create
        Create a new system file
        example: ptconfigure file create --file="somename"
                    --overwrite-existing # overwrite files that exist
                    --data="things to put in the file" # data for putting in the file

        - delete
        Delete a system file
        example: ptconfigure file delete --file="somename"

        - exists
        Check the existence of a file
        example: ptconfigure file exists --filename="somename"

        - append
        Append a line to a file
        example: ptconfigure file append --filename="somename" --line="a line"

        - should-have-line
        Ensure that a file contains a particular line
        example: ptconfigure file should-have-line --filename="somename" --line="a line"

HELPDATA;
      return $help ;
    }

}