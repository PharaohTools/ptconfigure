<?php

Namespace Info;

class RubyRVMInfo extends CleopatraBase {

  public $hidden = false;

  public $name = "Ruby RVM - The Ruby version manager";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "RubyRVM" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("rubyrvm"=>"RubyRVM", "rubyRVM"=>"RubyRVM", "ruby-rvm"=>"RubyRVM");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install Ruby RVM.

  RubyRVM, rubyrvm, ruby-rvm, rubyRVM

        - install
        Installs Ruby RVM
        example: cleopatra ruby-rvm install

  Ruby is installed the recommended per-user way. To use ruby after the install
  first run "source ~/.rvm/scripts/rvm" to get access to the Ruby install for
  your user, then "rvm install 1.9.3" (to install, specify version as needed)
  then "rvm use 1.9.3" (to select your default version for the session)

HELPDATA;
    return $help ;
  }

}