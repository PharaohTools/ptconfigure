<?php

Namespace Info;

class PTSourceDesktopGUIInfo extends PTConfigureBase {

  public $hidden = false;

  public $name = "The Pharaoh Virtualize GUI";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "PTSourceDesktopGUI" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("ptsgui"=>"PTSourceDesktopGUI", "ptsource-gui"=>"PTSourceDesktopGUI", "ptsourcegui"=>"PTSourceDesktopGUI");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This module allows you to install Pharaoh Source Desktop GUI.

  PTSourceDesktopGUI, ptsource-gui, ptsourcegui

        - install
        Installs Pharaoh Source Desktop GUI.
        
        example: ptconfigure ptsourcegui install


HELPDATA;
    return $help ;
  }

}