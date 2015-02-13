<?php

Namespace Info;

class SVNInfo extends Base {

    public $hidden = false;

    public $name = "SVN Source Control Project Checkout/Download Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "SVN" => array_merge(parent::routesAvailable(), array("checkout", "co") ) );
    }

    public function routeAliases() {
      return array("svn" => "SVN");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Default Modules and handles Checkout Functions.

  Checkout, checkout, co

          - perform a checkout into configured projects folder. If you don't want to specify target dir but do want
          to specify a branch, then enter the text "none" as that parameter.
          example: ptdeploy svn co https://svnhub.com/phpengine/yourmum {optional target dir} {optional branch}
          example: ptdeploy svn co https://svnhub.com/phpengine/yourmum none {optional branch}

HELPDATA;
      return $help ;
    }

}