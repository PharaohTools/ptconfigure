<?php

Namespace Info;

class BoxifyInfo extends PTConfigureBase {

  public $hidden = false;

  public $name = "Boxify Wrapper - Create Cloud Instances";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "Boxify" =>  array_merge(parent::routesAvailable(), array("box-add", "box-ensure", "box-destroy", "box-remove", "list-papyrus", "install-generic-autopilots", "gen") ) );
  }

  public function routeAliases() {
    return array("boxify"=>"Boxify");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This module provides a generic Box Management wrapper around all of the Box Providers (Cloud and Otherwise) so that we have a
  generic way to create and destroy boxes.

  Boxify, boxify

        - gen, install-generic-autopilots
        Install generic Boxify autopilot templates for a specified template group set of Environments
        example: ptconfigure boxify install-generic-autopilots
        example: ptconfigure boxify install-generic-autopilots
                    -yg
                    --group=tiny # specifiec a template group. defaults are tiny, medium, dbcluster
                    --destination-dir=*path-to-destination*# guess will *this dir* + build/config/ptconfigure/boxify/autopilots/

        - box-add
        Installs a Box through a cloud provider
        example: ptconfigure boxify box-add --environment-name="*environment*"
            --server-prefix="my-app"
            --provider="DigitalOcean" // DigitalOcean, Rackspace, VSphere
            --image-id="14530089" // DO=14530089 , RAX=ffd597d6-2cc4-4b43-b8f4-b1006715b84e
            --size-id="512mb" // DO = 512mb, RAX = 2
            --region-id="lon1" // DO = lon1, RAX = LON
            --box-amount=1 // An Integer number of boxes to create
            --force-name="a-box-name" // optional, will override other options for name creation. may cause a conflict if creating more than 1 box.
            --parallax // optional, when adding more than one box, if the provider supports it we can execute all requests in parallel

        - box-ensure
        Ensures the existence of Boxes through a cloud provider
        example: ptconfigure boxify box-ensure --environment-name="*environment*"
            --server-prefix="my-app"
            --provider="DigitalOcean" // DigitalOcean, Rackspace, VSphere
            --image-id="14530089" // DO=14530089 , RAX=ffd597d6-2cc4-4b43-b8f4-b1006715b84e
            --size-id="512mb" // DO = 512mb, RAX = 2
            --region-id="lon1" // DO = lon1, RAX = LON
            --box-amount=1 // An Integer number of boxes to create or ensure
            --force-name="a-box-name" // optional, will override other options for name creation. may cause a conflict if creating more than 1 box.
            --parallax // optional, when adding more than one box, if the provider supports it we can execute all requests in parallel
            --rebuild-failures // optional, when adding more than one box, if the provider supports it we can execute all requests in parallel

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