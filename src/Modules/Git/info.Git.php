<?php

Namespace Info;

class GitInfo extends Base {

    public $hidden = false;

    public $name = "Git Source Control Project Checkout/Download Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Git" => array_merge(parent::routesAvailable(), array("checkout", "co") ) );
    }

    public function routeAliases() {
      return array("git" => "Git");
    }

    public function autoPilotVariables() {
      return array(
              "Git" => array(
                "gitCheckoutExecute" => array(
                  "gitCheckoutExecute" => "boolean",
                  "gitCheckoutProjectOriginRepo"=>"string",
                  "gitCheckoutCustomCloneFolder"=>"string",
                  "gitCheckoutCustomBranch"=>"string",
                  "gitCheckoutWebServerUser"=>"string"),
                "gitDeletorExecute" => array(
                  "gitDeletorExecute" => "boolean",
                  "gitDeletorCustomFolder" => "string" )
              )
             );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Default Modules and handles Checkout Functions.

  Checkout, checkout, co

          - perform a checkout into configured projects folder. If you don't want to specify target dir but do want
          to specify a branch, then enter the text "none" as that parameter.
          example: dapperstrano git co https://github.com/phpengine/yourmum {optional target dir} {optional branch}
          example: dapperstrano git co https://github.com/phpengine/yourmum none {optional branch}

HELPDATA;
      return $help ;
    }

}