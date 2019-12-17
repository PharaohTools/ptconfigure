<?php

Namespace Info;

class DapperfyInfo extends Base {

    public $hidden = false;

    public $name = "PTDeploy Dapperfyer - Automated Application Deployment autopilots for your project";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Dapperfy" =>  array_merge(
          parent::routesAvailable(),
          array("create", "standard"),
          $this->getExtraRoutes()
      ) );
    }

    public function routeAliases() {
      return array("dapperfy"=>"Dapperfy");
    }

    public function dependencies() {
        return array("EnvironmentConfig");
    }

    public function helpDefinition() {
        $extraHelp = $this->getExtraHelpDefinitions() ;
        $help = <<<"HELPDATA"
  This command is part of a default Module Core and provides you with a method by which you can
  create a standard set of Autopilot files for your project from the command line.
  You can configure default application settings, ie: mysql admin user, host, pass


  Dapperfy, dapperfy

        - list
        List all of the autopilot files in your build/config/ptdeploy/autopilots
        example: ptdeploy dapperfy list

        - standard
        Create a standard set of autopilots to manage
        example: ptdeploy dapperfy standard

        The start of the command will be ptdeploy autopilot execute *filename*

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
                if (in_array("Dapperfy", $defNames)) {
                    $defs = $info->helpDefinitions() ;
                    $thisDef = $defs["Dapperfy"] ;
                    $extraDefsText .= $thisDef ; } } }
        return $extraDefsText ;
    }

    protected function getExtraRoutes() {
        $extraActions = array() ;
        $infos = \Core\AutoLoader::getInfoObjects() ;
        foreach ($infos as $info) {
            if (method_exists($info, "dapperfyActions")) {
                $extraActions = array_merge($extraActions, $info->dapperfyActions()); } }
        return $extraActions ;
    }

}