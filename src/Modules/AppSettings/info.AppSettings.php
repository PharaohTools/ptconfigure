<?php

Namespace Info;

class AppSettingsInfo extends Base {

    public $hidden = false;

    public $name = "PTDeploy Application Settings";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "AppSettings" =>  array_merge(parent::routesAvailable(), array("set", "get", "list", "delete") ) );
    }

    public function routeAliases() {
      return array("appsettings"=>"AppSettings");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Default Modules and provides you  with a method by which you can configure Application Settings.
  You can configure default application settings, ie: mysql admin user, host, pass

  AppSettings, appsettings

        - set
        Set a configuration value
        example: ptdeploy appsettings set

        - get
        Get the value of a setting you have configured
        example: ptdeploy appsettings get

        - delete
        Delete a setting you have configured
        example: ptdeploy appsettings delete

        - list
        Display a list of all default available settings
        example: ptdeploy appsettings list

HELPDATA;
      return $help ;
    }

}