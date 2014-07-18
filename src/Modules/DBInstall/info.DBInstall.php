<?php

Namespace Info;

class DBInstallInfo extends Base {

    public $hidden = false;

    public $name = "Database Installation Management Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "DBInstall" => array_merge(parent::routesAvailable(),
        array("install", "drop", "useradd", "userdrop", "save") ) );
    }

    public function routeAliases() {
      return array("dbinstall"=>"DBInstall", "db-install"=>"DBInstall");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Default Modules and handles Database Installation Functions.

  DBInstall, db-install, dbinstall

          - install
          install the database for a project. run conf first to set up users unless you already have them.
          example: dapperstrano db install

          - save
          save the database for a project. run conf first to set up users unless you already have them.
          example: dapperstrano db save

          - drop
          drop the database for a project.
          example: dapperstrano db drop

HELPDATA;
      return $help ;
    }

}