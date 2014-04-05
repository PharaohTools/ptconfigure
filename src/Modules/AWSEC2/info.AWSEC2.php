<?php

Namespace Info;

class AWSEC2Info extends CleopatraBase {

    public $hidden = false;

    public $name = "AWS EC2 Server Management Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "AWSEC2" => array_merge(parent::routesAvailable(), array("save-ssh-key",
          "box-add", "box-remove", "box-destroy", "destroy-all-droplets", "list") ) );
    }

    public function routeAliases() {
      return array("awsec2"=>"AWSEC2", "aws-ec2"=>"AWSEC2");
    }

    public function boxProviderName() {
        return "AWSEC2";
    }

    public function helpDefinition() {
       $help = <<<"HELPDATA"
    This is an extension provided for Handling Servers on AWS EC2.

    AWSEC2, awsec2, aws-ec2

        - save-ssh-key
        Will let you save a local ssh key to your AWS EC2 account, so you can ssh in to your nodes
        securely and without a password
        example: dapperstrano aws-ec2 save-ssh-key

        - list
        Will display data about your digital ocean account
        example: dapperstrano aws-ec2 list

HELPDATA;
      return $help ;
    }

}