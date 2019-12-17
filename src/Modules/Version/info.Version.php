<?php

Namespace Info;

class VersionInfo extends Base {

    public $hidden = false;

    public $name = "Versioning Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Version" => array_merge(parent::routesAvailable(),
        array("cli", "latest", "rollback", "specific") ) );
    }

    public function routeAliases() {
      return array("version" => "Version", "vrs" => "Version");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Default Modules and handles Application Versioning, allowing for rollbacks and the like.

  Version, version, vrs

          - specific
          Will change back the *current* symlink to whichever available version you pick
          example: ptdeploy version specific --limit=4 --container=/var/www/applications/the-app --version=2

          - latest
          Will change back the *current* symlink to the latest created version
          example: ptdeploy version latest
          example: ptdeploy version latest --limit=3 --container=/var/www/applications/the-app

          - rollback
          Will change back the *current* symlink to the latest created version but one
          example: ptdeploy version rollback
          example: ptdeploy version rollback --limit=3 --container=/var/www/applications/the-app


      You can also apply a limit to the number of Versions to keep by using the --limit parameter
          example: ptdeploy version latest --limit=3

HELPDATA;
      return $help ;
    }

}