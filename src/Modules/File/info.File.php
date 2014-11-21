<?php

Namespace Info;

class FileInfo extends CleopatraBase {

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
  This command allows you to modify files or check their existence

  File, file

        - create
        Create a new system file
        example: cleopatra file create --file="somename"
                    --overwrite-existing # overwrite files that exist
                    --data="things to put in the file" # data for putting in the file

        - delete
        Delete a system file
        example: cleopatra file delete --file="somename"

        - exists
        Check the existence of a file
        example: cleopatra file exists --filename="somename"

        - append
        Append a line to a file
        example: cleopatra file append --filename="somename" --line="a line"

        - should-have-line
        Ensure that a file contains a particular line
        example: cleopatra file should-have-line --filename="somename" --line="a line"

HELPDATA;
      return $help ;
    }

}