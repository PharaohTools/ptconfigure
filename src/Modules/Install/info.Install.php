<?php

Namespace Info;

class InstallInfo {

    public $hidden = false;

    public $name = "Installation Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Install" => array("cli", "autopilot") );
    }

    public function routeAliases() {
      return array("install" => "Install");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and handles default Install.

  Install, install

          - cli
          install a full web project - Checkout, VHost, Hostfile, Cucumber Configuration, Database Install and
          Settings Config, and Jenkins Job. The installer will ask you for required values
          example: devhelper install cli

          - autopilot
          perform an "unattended" install using the defaults in an autopilot file. Great for Remote Builds.
          example: devhelper install autopilot

HELPDATA;
      return $help ;
    }

}