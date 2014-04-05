<?php

Namespace Info;

class SshHardenInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Apply security functions to the SSH accounts/setup of the machine";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "SshHarden" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "SshHarden" =>  array_merge(
            array("help", "status", "securify")
        ) );
    }

    public function routeAliases() {
        return array("sshharden"=>"SshHarden", "ssh-harden"=>"SshHarden");
    }

    public function dependencies() {
        return array("Service");
    }

    public function autoPilotVariables() {
      return array(
        "SshHarden" => array(
          "SshHarden" => array(
            "programDataFolder" => "", // command and app dir name
            "programNameMachine" => "sshharden", // command and app dir name
            "programNameFriendly" => "    SshHarden    ", // 12 chars
            "programNameInstaller" => "SshHarden",
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to modify create or modify sshhardens

  SshHarden, sshharden, ssh-harden

        - securify
        Add some security to your SSH accounts
        example: cleopatra ssh-harden securify

HELPDATA;
      return $help ;
    }

}