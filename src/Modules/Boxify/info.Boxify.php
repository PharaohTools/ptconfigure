<?php

Namespace Info;

class BoxifyInfo extends PTConfigureBase {

  public $hidden = false;

  public $name = "Boxify Wrapper - Create Cloud Instances";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "Boxify" =>  array_merge(parent::routesAvailable(), array("box-add", "box-destroy", "box-remove", "list-papyrus", "install-generic-autopilots", "gen") ) );
  }

  public function routeAliases() {
    return array("boxify"=>"Boxify");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command provides a generic Box Management wrapper around all of the Box Providers (Cloud and Otherwise) so that we have a
  generic way to create and destroy boxes.

  Boxify, boxify

        - gen, install-generic-autopilots
        Install generic Boxify autopilot templates for a specified template group set of Environments
        example: ptconfigure boxify install-generic-autopilots
        example: ptconfigure boxify install-generic-autopilots
                    -yg
                    --template-group=tiny
                    --destination-dir=*path-to-destination*# guess will *this dir* + build/config/ptconfigure/boxify/autopilots/

        - box-add
        Installs a Box through a cloud provider
        example: ptconfigure boxify box-add --environment-name="*environment*"
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
        example: ptconfigure boxify box-remove --environment-name="staging" --environment-version="5.0" --provider="apt-get"

        - box-destroy
        Removes a Box from both papyrus and the cloud provider
        example: ptconfigure boxify box-destroy --environment-name="staging"
            --destroy-all-boxes
            --destroy

        - list-papyrus
        List all servers in papyrus, or those of a particular environment
        example: ptconfigure boxify list-papyrus --yes
        example: ptconfigure boxify list-papyrus --yes --environment-name="staging"


HELPDATA;
    return $help ;
  }

}