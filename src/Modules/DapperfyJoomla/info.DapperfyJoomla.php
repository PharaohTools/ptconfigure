<?php

Namespace Info;

class DapperfyJoomlaInfo extends DapperfyInfo {

    public $hidden = false;

    public $name = "Dapperstrano Dapperfyer for Joomla - Create some standard autopilots for your project";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "DapperfyJoomla" =>  array_merge(parent::routesAvailable(), array("create-joomla3x", "create-joomla15") ) );
    }

    public function routeAliases() {
      return array("dapperfy-joomla"=>"DapperfyJoomla", "dapperfyjoomla"=>"DapperfyJoomla");
    }



    public function helpDefinition() {
      $infoDapperfyParent = new \Info\DapperfyInfo();
      $help = $infoDapperfyParent->helpDefinition();
      $help .= <<<"HELPDATA"
  This Extension is for Dapperfying with extra functions for Joomla.


  DapperfyJoomla, dapperfy-joomla, dapperfyjoomla

        - joomla3x
        Create a set of autopilots for deploying your Joomla 3.x Applications
        example: dapperstrano dapperfy list

        - joomla15
        Create a set of autopilots for deploying your Joomla 1.5 Applications
        example: dapperstrano dapperfy create

HELPDATA;
      return $help ;
    }

}