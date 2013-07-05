<?php

Namespace Info;

class CheckoutInfo extends Base {

    public $hidden = false;

    public $name = "Source Control Project Checkout/Download Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Checkout" => array_merge(parent::routesAvailable(), array("git") ) );
    }

    public function routeAliases() {
      return array("co"=>"Checkout", "checkout"=>"Checkout");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and handles Checkout Functions. Currently it only handles Git, but adding SVN wont take too long.

  checkout, co

          - perform a checkout into configured projects folder. If you don't want to specify target dir but do want
          to specify a branch, then enter the text "none" as that parameter.
          example: devhelper co git https://github.com/phpengine/yourmum {optional target dir} {optional branch}
          example: devhelper co git https://github.com/phpengine/yourmum none {optional branch}

HELPDATA;
      return $help ;
    }

}