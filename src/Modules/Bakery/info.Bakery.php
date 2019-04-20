<?php

Namespace Info;

class BakeryInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Bakery - Create OS Images for Multiple Platforms";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Bakery" =>  array_merge(parent::routesAvailable(), array("osinstall", 'bake') ) );
    }

    public function routeAliases() {
      return array("bakery"=>"Bakery");
    }

    public function helpDefinition() {
      $mod_path = PFILESDIR.PHARAOH_APP.DS.PHARAOH_APP.DS.'src'.DS.'Modules'.DS.'Bakery' ;
      $help = <<<"HELPDATA"
  This module allows you to create OS Images for multiple platforms. Create Base images installed from Operating System
  ISO Files or Configured Images using Pharaoh Configure DSL Scripts too.

  Bakery, bakery

        - osinstall
        Installs an Operating System from its base ISO Image, into a Box package usable by Virtualize
        example: ptconfigure bakery osinstall # will ask for below mentioned parameters
        example: ptconfigure bakery osinstall --iso="ubuntu.iso" --yes --guess
        example: ptconfigure bakery osinstall
                                      --iso="ubuntu.iso"
                                      --name="ptv_bakery_temp_vm" # Optional
                                      --ostype="Ubuntu_64" # Optional
                                      --memory="512" # Optional
                                      --vram="33" # Optional
                                      --cpus="1" # Optional
                                      --ssh_forwarding_port="9988" # Optional

        - bake
        Create a new Box Package from a Base Box, including specified configurations, into format of your choice
        example: ptconfigure bakery bake # will install 1.7
        example: ptconfigure bakery bake --bakery-install-version=1.7
        example: ptconfigure bakery bake --bakery-install-version=1.8
        
  We have also included examples of Autopilot files which would be used with Bakery. With Autopilots set up in this
  way, you can create a complete.
  
  ptconfigure auto x --af={$mod_path}/Autopilots/PTConfigure/osinstall.dsl.php \ 
                     --vars={$mod_path}/Autopilots/PTConfigure/vars.php 


HELPDATA;
      return $help ;
    }

}