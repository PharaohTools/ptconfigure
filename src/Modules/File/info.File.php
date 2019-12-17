<?php

Namespace Info;

class FileInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Functions to Modify Files";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        return array( "File" =>  array_merge( array("help", "create", "fill", "populate", "set", "delete", "exists",
            "should-exist", "append", "should-have-line", "should-not-have-line", "replace-line") ) );
    }

    public function routeAliases() {
      return array("file"=>"File");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"

  This module allows you to modify files or check their existence. The Parameters file and path are interchangeable

  File, file

        - create, fill, populate, set
        Create a new system file
        example: ptconfigure file create
            --file="/some/file/path"
            --overwrite-existing # overwrite files that exist
            --data="things to put in the file" # data for putting in the file

        - delete
        Delete a system file
        example: ptconfigure file delete --file="/some/file/path"
        example: ptconfigure file delete --file="/some/file/path"

        - exists
        Check the existence of a file
        example: ptconfigure file exists --file="/some/file/path"

        - should-exist
        Ensure that a file exists at a path, or create an empty one
        example: ptconfigure file exists --file="/some/file/path"

        - append
        Append a line to a file
        example: ptconfigure file append --file="/some/file/path" --line="a line"

        - should-have-line
        Ensure that a file contains a particular line
        example: ptconfigure file should-have-line --file="/some/file/path" --search="a subject"

        - should-not-have-line
        Ensure that a file does not contain a particular line
        example: ptconfigure file should-not-have-line --file="/some/file/path" --search="a subject"

        - replace-line
        Replace a particular line if it's found in the file
        example: ptconfigure file replace-line --file="/some/file/path" --search="a subject" --replace="a replacment"

        - replace-text
        Replace an arbritrary sized string (including \n, \r, PHP_EOL) if it's found in the file
        example: ptconfigure file replace-line --file="/some/file/path" --search="a subject" --replace="a replacment"



HELPDATA;
      return $help ;
    }

}