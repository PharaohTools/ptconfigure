<?php

Namespace Info;

class DigitalOceanInfo extends Base {

    public $hidden = false;

    public $name = "Digital Ocean Server Management Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "DigitalOcean" => array_merge(parent::routesAvailable(), array("save-ssh-key",
          "box-add", "box-remove", "box-destroy", "destroy-all-droplets", "list") ) );
    }

    public function routeAliases() {
      return array("digitalocean"=>"DigitalOcean", "digital-ocean"=>"DigitalOcean");
    }

    public function boxProviderName() {
        return "DigitalOcean";
    }

    public function helpDefinition() {
       $help = <<<"HELPDATA"
    This is an extension provided for Handling Servers on Digital Ocean.

    DigitalOcean, digitalocean, digital-ocean

        - save-ssh-key
        Will let you save a local ssh key to your Digital Ocean account, so you can ssh in to your nodes
        securely and without a password
        example: dapperstrano digital-ocean save-ssh-key

        - list
        Will display data about your digital ocean account
        example: dapperstrano digital-ocean list

HELPDATA;
      return $help ;
    }

}