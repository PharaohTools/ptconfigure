<?php

Namespace Info;

class DNSifyInfo extends CleopatraBase {

  public $hidden = false;

  public $name = "DNSify Wrapper - Create Cloud Instances";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "DNSify" =>  array_merge(parent::routesAvailable(), array("box-add", "box-destroy", "box-remove", "list-papyrus", "install-generic-autopilots") ) );
  }

  public function routeAliases() {
    return array("boxify"=>"DNSify");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command provides a generic Box Management wrapper around all of the Box Providers (Cloud and Otherwise) so that we have a
  generic way to create and destroy boxes.

  DNSify, boxify

        - install-generic-autopilots
        Install the generic DNSify autopilot templates for a Tiny or Medium (Current Default) set of Environments
        example: cleopatra boxify install-generic-autopilots
        example: cleopatra boxify install-generic-autopilots
                    --yes
                    --guess # will set --destination-dir=*this dir +*build/config/cleopatra/boxify/autopilots/
                    --template-group=tiny
                    --destination-dir=*path-to-destination*

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
            --parallax // optional, when adding more than one box, if the provider supports it we can execute all requests in parallel

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


HELPDATA;
    return $help ;
  }

}