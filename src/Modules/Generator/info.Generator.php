<?php

Namespace Info;

class GeneratorInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Generator Functionality";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Generator" => array_merge(array("help", "copy") ) );
    }

    public function routeAliases() {
      return array(
          "generator" => "Generator", "gen" => "Generator", "generate" => "Generator",
          "genmod" => "Generator");
    }

  public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module handles module generation functions.

  Generator, generator, gen

        - copy
        Will create a copy from an existing local Module
        example: ptconfigure gen copy
        example: ptconfigure gen copy -yg --source="SourceModule" --target="TargetModule"

        - template
        Will create a copy from a Downloadable Template Module
        example: ptconfigure gen template
        example: ptconfigure gen template -yg --source="{git repo}" --target="TargetModule"
        example: ptconfigure gen template -yg --source="{git repo}" --target="TargetModule" --key="/home/me/.ssh"
        example: ptconfigure gen template -yg --source="{git repo}" --target="TargetModule" --credentials={cred_name}

HELPDATA;
      return $help ;
    }

}