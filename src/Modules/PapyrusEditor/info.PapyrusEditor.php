<?php

Namespace Info;

class PapyrusEditorInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Papyrus Editor Web Interface";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PapyrusEditor" =>  array("help", "start", "install-interface") );
    }

    public function routeAliases() {
      return array("papyruseditor" => "PapyrusEditor", "papyrus-editor" => "PapyrusEditor");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module provides a web interface for modifying papyrus files.


HELPDATA;
      return $help ;
    }

}