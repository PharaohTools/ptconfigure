<?php

Namespace Info;

class MediaToolsInfo extends CleopatraBase {

  public $hidden = false;

  public $name = "Media Tools - Tools to help view and manage Media files";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "MediaTools" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("media-tools"=>"MediaTools", "mediatools"=>"MediaTools");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install a few GC recommended Media Tools
  for productivity in your system. Currently, we're only including
  VLC Media Player

  MediaTools, media-tools, mediatools, mediatools, media-tools

        - install
        Installs some media tools
        example: cleopatra mediatools install

HELPDATA;
    return $help ;
  }

}