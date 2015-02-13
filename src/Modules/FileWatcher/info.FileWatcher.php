<?php

Namespace Info;

class FileWatcherInfo extends PTConfigureBase {

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

        - once
        Will watch an individual file for changes and perform actions
        example: ptconfigure filewatcher once --yes --guess
                    --file=relative/path/to/file
                    --versioner=git # git/svn/cache guess will assume git
                    --value="HEAD~1" # commit id, or other comparison value
                    --success-callback='echo "file has changed"' # a callback command to execute upon finding a file change
                    --failure-callback='echo "file has not changed"' # a callback command to execute upon finding no file changes
                    --escalate # exit this command using the status of the callback as opposed to the status of the watch check

        - watchfile
        Will watch files for changes and perform actions
        example: ptconfigure filewatcher watchfile --yes --guess
                    --watchfile=/path/to/watchfile # guess will assume Watchfile

HELPDATA;
      return $help ;
    }

}