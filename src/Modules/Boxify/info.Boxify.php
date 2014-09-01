<?php

Namespace Info;

class BoxifyInfo extends CleopatraBase {

  public $hidden = false;

  public $name = "Boxify Wrapper - Create Cloud Instances";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "Boxify" =>  array_merge(parent::routesAvailable(), array("box-add", "box-destroy", "box-remove", "list-papyrus", "install-generic-autopilots") ) );
  }

  public function routeAliases() {
    return array("boxify"=>"Boxify");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to Boxify a Box Management wrapper.

  Boxify, boxify

        - box-add
        Installs a Box through a cloud provider
        example: cleopatra boxify box-add --environment-name="*environment*"
            --server-prefix="my-app"
            --provider="DigitalOcean" // DigitalOcean, Rackspace, VSphere
            --image-id="3101045" // DO=3101045 , RAX=ffd597d6-2cc4-4b43-b8f4-b1006715b84e
            --size-id="66" // DO = 66, RAX = 2
            --region-id="2" // DO = 2, RAX = LON
            --box-amount=1 // An Integer number of boxes to create
            --force-name="a-box-name" // optional, will override other options for name creation. may cause a conflict if creating more than 1 box.

        - box-remove
        Removes a Box from the papyrus
        example: cleopatra boxify box-remove --environment-name="staging" --environment-version="5.0" --provider="apt-get"

        - box-destroy
        Removes a Box from both papyrus and the cloud provider
        example: cleopatra boxify box-destroy --environment-name="staging"
            --destroy-all-boxes
            --destroy

        - list-papyrus
        List all servers in papyrus, or those of a particular environment
        example: cleopatra boxify list-papyrus --yes
        example: cleopatra boxify list-papyrus --yes --environment-name="staging"

        - install-generic-autopilots
        Install the generic Boxify autopilot templates for a Tiny or Medium (Current Default) set of Environments
        example: cleopatra boxify install-generic-autopilots
        example: cleopatra boxify install-generic-autopilots
                    --yes
                    --guess # will set --destination-dir=*this dir +*build/config/cleopatra/boxify/autopilots/
                    --template-group=tiny
                    --destination-dir=*path-to-destination*

  A environment manager wrapper that will allow you to install environments on any system

HELPDATA;
    return $help ;
  }

}