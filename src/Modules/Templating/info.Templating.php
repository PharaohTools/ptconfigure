<?php

Namespace Info;

class TemplatingInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Install files with placeholders or lines replaced at runtime";

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
  This module allows you to install a templated file with new values.

  Templating, templating, template

        - install
        Installs a template
        example: ptconfigure template install
        example: ptconfigure template install -yg
        example: ptconfigure template install -yg --source="" --target=""
        example: ptconfigure template install -yg --template_foo="bar" # prefix your replacements with template_
        
        Placeholders for Variables within your files should be expressed in the form of <%tpl.php%>foo</%tpl.php%>

HELPDATA;
      return $help ;
    }

}