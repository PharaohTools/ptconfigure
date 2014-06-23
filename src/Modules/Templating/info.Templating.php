<?php

Namespace Info;

class TemplatingInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Install files with placeholders replaced at runtime";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Templating" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("templating"=>"Templating", "template"=>"Templating");
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