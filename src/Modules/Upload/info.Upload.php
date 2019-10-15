<?php

Namespace Info;

class UploadInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Upload Functionality";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Upload" => array_merge(parent::routesAvailable(), array("file") ) );
    }

    public function routeAliases() {
      return array("upload" => "Upload");
    }

  public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module handles HTTP File Upload Functions.

  Upload, upload

        - file
        Will ask you for a Source URL, Target IP, Authentication Method / Credentials to upload a File
        example: ptconfigure upload file
        example: ptconfigure upload file --yes --source="/tmp/myfile.html" --target="http://www.google.co.uk"

HELPDATA;
      return $help ;
    }

}