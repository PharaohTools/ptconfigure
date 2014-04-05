<?php

Namespace Info;

class AWSCloudFormationInfo extends CleopatraBase {

  public $hidden = false;

  public $name = "The AWS CloudFormation CLI Tools";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "AWSCloudFormation" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("awscloudformation"=>"AWSCloudFormation", "aws-cloud-formation"=>"AWSCloudFormation",
        "aws-cloudformation"=>"AWSCloudFormation");
  }

  public function autoPilotVariables() {
    return array(
      "AWSCloudFormation" => array(
        "AWSCloudFormation" => array(
          "programDataFolder" => "/opt/AWSCloudFormation", // command and app dir name
          "programNameMachine" => "aws-cloud-formation", // command and app dir name
          "programNameFriendly" => "AWSCloudFormation Srv", // 12 chars
          "programNameInstaller" => "AWSCloudFormation Server",
        ),
      )
    );
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install a The AWS Cloud Formation Command
  Line Tools. This tool is provided by

  AWSCloudFormation, aws-cloud-formation, aws-cloudformation

        - install
        Installs AWSCloudFormation. Note, you'll also need Java installed
        as it is a prerequisite for AWSCloudFormation.
        example: cleopatra aws-cloud-formation install

HELPDATA;
    return $help ;
  }

}