<?php

Namespace Info;

class DBConfigureInfo extends Base {

    public $hidden = false;

    public $name = "Database Connection Configuration Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "DBConfigure" => array_merge(
          parent::routesAvailable(),
          array("configure", "config", "conf", "reset"),
          $this->getExtraRoutes()
      ) );
    }

    public function routeAliases() {
      return array("dbconfigure"=>"DBConfigure", "db-configure"=>"DBConfigure", "db-conf"=>"DBConfigure");
    }

    public function helpDefinition() {
        $extraHelp = $this->getExtraHelpDefinitions() ;
        $help = <<<"HELPDATA"
  This command is part of Default Modules and handles Databasing Functions.

  DBConfigure, db-configure, dbconfigure, db-conf

      - configure, conf
      set up db user & pw for a project, use admins to create new resources as needed.
      example: ptdeploy db-conf conf drupal
      example: ptdeploy db-conf conf --yes --platform=joomla30 --mysql-host=127.0.0.1 --mysql-admin-user="" --mysql-user="impi_dv_user" --mysql-pass="impi_dv_pass" --mysql-db="impi_dv_db"

      - reset
      reset current db to generic values so ptdeploy can write them. may need to be run before db conf.
      example: ptdeploy db-conf reset drupal
      example: ptdeploy db-conf reset --yes --platform=joomla30

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
                if (in_array("DBConfigure", $defNames)) {
                    $defs = $info->helpDefinitions() ;
                    $thisDef = $defs["DBConfigure"] ;
                    $extraDefsText .= $thisDef ; } } }
        return $extraDefsText ;
    }

    protected function getExtraRoutes() {
        $extraActions = array() ;
        $infos = \Core\AutoLoader::getInfoObjects() ;
        foreach ($infos as $info) {
            if (method_exists($info, "dbConfigureActions")) {
                $extraActions = array_merge($extraActions, $info->dbConfigureActions()); } }
        return $extraActions ;
    }


}