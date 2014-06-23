<?php

Namespace Info;

class UbuntuCompilerInfo extends CleopatraBase {

  public $hidden = false;

  public $name = "For Compiling Linux Programs";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "UbuntuCompiler" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("ubuntu-compiler"=>"UbuntuCompiler", "ubuntucompiler"=>"UbuntuCompiler");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This allows you to Complie programs written in C Source

  UbuntuCompiler, ubuntu-compiler, ubuntucompiler

        - install
        Installs Ubuntu Compiling tools through apt-get.
        example: cleopatra ubuntu-compiler install

HELPDATA;
    return $help ;
  }

}