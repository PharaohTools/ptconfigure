<?php

Namespace Info;

class RubySystemInfo extends CleopatraBase {

  public $hidden = false;

  public $name = "Ruby RVM System wide - The Ruby version manager system wide version";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "RubySystem" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("rubysystem"=>"RubySystem", "ruby-system"=>"RubySystem", "rubysys"=>"RubySystem");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install Ruby RVM, the system wide version.

  RubySystem, rubysystem, ruby-system, rubysys

        - install
        Installs Ruby a System Wide version of Ruby for you
        example: cleopatra ruby-rvm install

  Ruby is installed the recommended per-user way. To use ruby after the install
  first run "source ~/.rvm/scripts/rvm" to get access to the Ruby install for
  your user, then "rvm install 1.9.3" (to install, specify version as needed)
  then "rvm use 1.9.3" (to select your default version for the session)

HELPDATA;
    return $help ;
  }

}