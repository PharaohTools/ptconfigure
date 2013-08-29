<?php

Namespace Info;

class RubyRVMInfo extends Base {

  public $hidden = false;

  public $name = "RubyRVM";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "RubyRVM" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("rubyrvm"=>"RubyRVM", "rubyRVM"=>"RubyRVM", "ruby-rvm"=>"RubyRVM");
  }

  public function autoPilotVariables() {
    return array(
      "RubyRVM" => array(
        "RubyRVM" => array(
          "programDataFolder" => "/opt/RubyRVM", // command and app dir name
          "programNameMachine" => "rubyrvm", // command and app dir name
          "programNameFriendly" => "Ruby RVM!", // 12 chars
          "programNameInstaller" => "Ruby RVM - Ruby Version Manager",
          "installUserName" => "string",
          "installUserHomeDir" => "string",
        ),
      )
    );
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