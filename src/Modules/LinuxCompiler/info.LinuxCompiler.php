<?php

Namespace Info;

class LinuxCompilerInfo extends PTConfigureBase {

  public $hidden = false;

  public $name = "For Compiling Linux Programs";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "LinuxCompiler" =>  array_merge(
        parent::routesAvailable(), array("install", "archive", "directory") ) );
  }

  public function routeAliases() {
    return array("linux-compiler"=>"LinuxCompiler", "linuxcompiler"=>"LinuxCompiler");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This allows you to Complie programs written in C Source

  LinuxCompiler, linux-compiler, linuxcompiler

        - install
        Installs Linux tools for Compiling software
        example: ptconfigure linux-compiler install

        - archive
        Installs compiled Linux Software from an archive
        example: ptconfigure linux-compiler archive

        - directory
        Installs compiled Linux Software from a directory
        example: ptconfigure linux-compiler directory

HELPDATA;
    return $help ;
  }

}