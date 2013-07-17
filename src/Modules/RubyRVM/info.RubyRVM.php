<?php

Namespace Info;

class RubyRVMInfo extends Base {

  public $hidden = false;

  public $name = "RubyRVM";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "RubyRVM" =>  array_merge(parent::defaultActionsAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("rubyrvm"=>"RubyRVM", "rubyRVM"=>"RubyRVM", "ruby-rvm"=>"RubyRVM");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install Ruby RVM

  RubyRVM, rubyrvm, ruby-rvm, rubyRVM

        - install
        Installs Ruby RVM
        example: cleopatra ruby-rvm install

HELPDATA;
    return $help ;
  }

}