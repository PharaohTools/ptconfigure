<?php

Namespace Info;

class DigitalOceanInfo extends CleopatraBase {

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
        example: cleopatra digital-ocean save-ssh-key
                    --yes
                    --digital-ocean-ssh-key-path="/home/dave/.ssh/bastion.pub"
                    --digital-ocean-ssh-key-name="bastion"

        - list
        Will display data about your digital ocean account
        example: cleopatra digital-ocean list
        example: cleopatra digital-ocean list --yes
                    --guess # use project saved connection details if possible
                    --digital-ocean-list-data-type=sizes # droplets, sizes, images, domains, regions, ssh_keys

HELPDATA;
      return $help ;
    }

}