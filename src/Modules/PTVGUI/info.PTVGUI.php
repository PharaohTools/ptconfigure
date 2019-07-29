<?php

Namespace Info;

class PTVGUIInfo extends PTConfigureBase {

  public $hidden = false;

  public $name = "The Pharaoh Virtualize GUI";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "PTVGUI" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("ptv-gui"=>"PTVGUI", "ptvgui"=>"PTVGUI", "ptvirtualizegui"=>"PTVGUI");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This module allows you to install Pharaoh Virtualize GUI.

  PTVGUI, ptv-gui, ptvgui, ptvirtualizegui

        - install
        Installs Pharaoh Virtualize GUI.
        
        example: ptconfigure ptvgui install


HELPDATA;
    return $help ;
  }

}