<?php

Namespace Info;

class GitCheckoutInfo {

    public $hidden = false;

    public $name = "Git Checkout Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "checkout" => array("git") );
    }

    public function routeAliases() {
      return array("co"=>"checkout");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and handles Git Checkout Functions.
HELPDATA;
      return $help ;
    }

}