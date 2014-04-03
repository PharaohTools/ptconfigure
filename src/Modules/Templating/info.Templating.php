<?php

Namespace Info;

class TemplatingInfo extends Base {

    public $hidden = false;

    public $name = "Templating";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Templating" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("templating"=>"Templating", "template"=>"Templating");
    }

    public function autoPilotVariables() {
      return array(
        "Templating" => array(
          "Templating" => array(
            "programDataFolder" => "/opt/Templating", // command and app dir name
            "programNameMachine" => "templating", // command and app dir name
            "programNameFriendly" => "Templating", // 12 chars
            "programNameInstaller" => "Sudo capability with No Password for a user",
            "installUserName" => "string"
          ),
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install a templated file with new values.

  Templating, templating, template

        - install
        Installs a template
        example: cleopatra template install

HELPDATA;
      return $help ;
    }

}