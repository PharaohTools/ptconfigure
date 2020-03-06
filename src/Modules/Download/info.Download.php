<?php

Namespace Info;

class DownloadInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Download Functionality";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Download" => array_merge(parent::routesAvailable(), array("file") ) );
    }

    public function routeAliases() {
      return array("download" => "Download");
    }

  public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module handles HTTP File Download Functions.

  Download, download

        - file
        Will ask you for a Source URL, and Download to a Target File
        example: ptconfigure download file
        example: ptconfigure download file --yes --source="http://www.google.co.uk" --target="/tmp/myfile.html"
        example: ptconfigure download file --yes --source="http://www.google.co.uk" --target="/tmp/myfile.html" --ignore-network --ignore-ssl --overwrite

HELPDATA;
      return $help ;
    }

}