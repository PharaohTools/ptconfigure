<?php

Namespace Info;

class FileWatcherInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "FileWatcher Functionality";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "FileWatcher" => array("path", "help") );
    }

    public function routeAliases() {
      return array("chown" => "FileWatcher");
    }

  public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command handles file user ownership changing functions.

  FileWatcher, chown

        - path
        Will change the user ownership of a path
        example: cleopatra chown path --yes --guess --recursive --path=/a/file/path --owner=golden

HELPDATA;
      return $help ;
    }

}