<?php

Namespace Info;

class FirewallInfo extends Base {

    public $hidden = false;

    public $name = "Add, Remove or Modify Firewalls";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
        // return array( "Firewall" =>  array_merge(parent::routesAvailable(), array() ) );
        return array( "Firewall" =>  array_merge(
            array("help", "status", "install", "enable", "disable", "allow", "deny", "reject", "limit", "delete", "insert", "reset")
        ) );
    }

    public function routeAliases() {
      return array("firewall"=>"Firewall");
    }

    public function autoPilotVariables() {
      return array(
        "Firewall" => array(
          "Firewall" => array(
            "programDataFolder" => "", // command and app dir name
            "programNameMachine" => "firewall", // command and app dir name
            "programNameFriendly" => "    Firewall    ", // 12 chars
            "programNameInstaller" => "Firewall",
          )
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to modify create or modify firewalls

  Firewall, firewall

        - enable
        Enable system firewall
        example: cleopatra firewall enable

        - disable
        Disable system firewall
        example: cleopatra firewall disable

        - allow
        Allow a Firewall rule
        example: cleopatra firewall allow --firewall-rule="ssh/tcp"

        - deny
        Deny a Firewall rule
        example: cleopatra firewall deny --firewall-rule="ssh/tcp"

        - reject
        Reject a Firewall rule
        example: cleopatra firewall reject --firewall-rule="ssh/tcp"

        - limit
        Limit a Firewall rule. ufw will deny connections if an IP address has attempted
        to initiate 6 or more connections in the last 30 seconds.
        example: cleopatra firewall limit --firewall-rule="ssh/tcp"

        - delete
        Delete a Firewall rule.
        example: cleopatra firewall delete --firewall-rule="ssh/tcp"

        - insert
        Insert a Firewall rule.
        example: cleopatra firewall insert --firewall-rule="ssh/tcp"

        - reset
        Reset a Firewall rule.
        example: cleopatra firewall reset --firewall-rule="ssh/tcp"

HELPDATA;
      return $help ;
    }

}