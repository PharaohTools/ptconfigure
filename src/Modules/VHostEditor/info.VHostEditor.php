<?php

Namespace Info;

class VHostEditorInfo extends Base {

    public $hidden = false;

    public $name = "Apache Virtual Host Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "VHostEditor" => array_merge(parent::routesAvailable(), array("add", "rm", "list") ) );
    }

    public function routeAliases() {
      return array("vhc"=>"VHostEditor", "vhosted"=>"VHostEditor", "vhed"=>"VHostEditor");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Core and handles Apache VHosts Functions.

  VHostEditor, vhosteditor, vhc, vhosted

          - add
          create a Virtual Host
          example: dapperstrano vhc add

          - rm
          remove a Virtual Host
          example: dapperstrano vhc rm

          - list
          List current Virtual Hosts
          example: dapperstrano vhc list

HELPDATA;
      return $help ;
    }

}