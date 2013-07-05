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
  This command is part of Core and handles Application Versioning, allowing for rollbacks and the like.

  Version, version, vrs

          - specific
          Will change back the *current* symlink to whichever available version you pick
          example: devhelper version cli

          - latest
          Will change back the *current* symlink to the latest created version
          example: devhelper version latest

          - rollback
          Will change back the *current* symlink to the latest created version but one
          example: devhelper version rollback

HELPDATA;
      return $help ;
    }

}