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
          example: dapperstrano version specific

          - latest
          Will change back the *current* symlink to the latest created version
          example: dapperstrano version latest

          - rollback
          Will change back the *current* symlink to the latest created version but one
          example: dapperstrano version rollback


      You can also apply a limit to the number of Versions to keep by using the --limit parameter
          example: dapperstrano version latest --limit=3

HELPDATA;
      return $help ;
    }

}