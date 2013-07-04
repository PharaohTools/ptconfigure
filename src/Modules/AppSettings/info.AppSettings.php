<?php

Namespace Info;

class AppSettingsInfo {

    public $hidden = false;

    public $name = "Dapperstrano Application Settings";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "AppSettings" => array("set", "get", "list", "delete") );
    }

    public function routeAliases() {
      return array("appsettings"=>"AppSettings");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and provides you  with a method by which you can configure Application Settings.
HELPDATA;
      return $help ;
    }

}