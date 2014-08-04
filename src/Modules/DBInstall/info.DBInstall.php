<?php

Namespace Info;

class DBInstallInfo extends Base {

    public $hidden = false;

    public $name = "Database Installation Management Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        return array( "DBInstall" => array_merge(
            parent::routesAvailable(),
            array("install", "drop", "useradd", "userdrop", "save"),
            $this->getExtraRoutes()
        ) );
    }

    public function routeAliases() {
        return array("dbinstall"=>"DBInstall", "db-install"=>"DBInstall");
    }

    public function helpDefinition() {
        $extraHelp = $this->getExtraHelpDefinitions() ;
        $help = <<<"HELPDATA"
  This command is part of Default Modules and handles Database Installation Functions.

  DBInstall, db-install, dbinstall

          - install
          install the database for a project. run DBConfigure first to set up users unless you already have them.
          example: dapperstrano db-install install

          - save
          save the database for a project. run DBConfigure first to set up users unless you already have them.
          example: dapperstrano db-install save

          - drop
          drop the database for a project.
          example: dapperstrano db-install drop

      $extraHelp
HELPDATA;
      return $help ;
    }

    protected function getExtraHelpDefinitions() {
        $extraDefsText = "" ;
        $infos = \Core\AutoLoader::getInfoObjects() ;
        foreach ($infos as $info) {
            if (method_exists($info, "helpDefinitions")) {
                $defNames = array_keys($info->helpDefinitions());
                if (in_array("DBInstall", $defNames)) {
                    $defs = $info->helpDefinitions() ;
                    $thisDef = $defs["DBInstall"] ;
                    $extraDefsText .= $thisDef ; } } }
        return $extraDefsText ;
    }

    protected function getExtraRoutes() {
        $extraActions = array() ;
        $infos = \Core\AutoLoader::getInfoObjects() ;
        foreach ($infos as $info) {
            if (method_exists($info, "dbInstallActions")) {
                $extraActions = array_merge($extraActions, $info->dbInstallActions()); } }
        return $extraActions ;
    }

}