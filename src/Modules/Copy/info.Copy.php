<?php

Namespace Info;

class CopyInfo extends CleopatraBase {

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
  This command handles Copy Transfer Functions.

  Copy, copy

        - put
        Will ask you for details for servers, then copy a file or directory from local to remote
        example: cleopatra copy put
        example: cleopatra copy put --yes --source="/tmp/file" --target="/home/user/file"

HELPDATA;
      return $help ;
    }

}