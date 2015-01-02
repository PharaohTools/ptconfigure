<?php

Namespace Info;

class FileWatcherInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "File Watcher - Wait for changes in files, with optional actions on changes";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "FileWatcher" => array("once", "watchfile", "help") );
    }

    public function routeAliases() {
      return array("filewatcher" => "FileWatcher", "file-watcher" => "FileWatcher");
    }

  public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command watches files user ownership changing functions.

  FileWatcher, filewatcher, file-watcher

        - change
        Will watch files for changes and perform actions
        example: cleopatra filewatcher watch --yes --guess
                    --watchfile=/path/to/watchfile # guess will assume Watchfile
                    --versionsource=git # git/svn/cache guess will assume git
                    --compare=02aeb1c38dec40cede28b36ba200ec3d5b67f22c # commit id, or other comparison value

HELPDATA;
      return $help ;
    }

}