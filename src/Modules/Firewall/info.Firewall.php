<?php

Namespace Info;

class FirewallInfo extends PTConfigureBase {

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

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to modify create or modify firewalls

  Firewall, firewall

        - enable
        Enable system firewall
        example: ptconfigure firewall enable

        - disable
        Disable system firewall
        example: ptconfigure firewall disable

        - allow
        Allow a Firewall rule
        example: ptconfigure firewall allow --firewall-rule="ssh/tcp"

        - deny
        Deny a Firewall rule. Allow connection attempts to be ignored and time out.
        example: ptconfigure firewall deny --firewall-rule="ssh/tcp"

        - reject
        Reject a Firewall rule. Terminate connections attempts with an error to the connector.
        example: ptconfigure firewall reject --firewall-rule="ssh/tcp"

        - limit
        Limit a Firewall rule. ufw will deny connections if an IP address has attempted
        to initiate 6 or more connections in the last 30 seconds.
        example: ptconfigure firewall limit --firewall-rule="ssh/tcp"

        - delete
        Delete a Firewall rule.
        example: ptconfigure firewall delete --firewall-rule="ssh/tcp"

        - insert
        Insert a Firewall rule.
        example: ptconfigure firewall insert --firewall-rule="ssh/tcp"

        - reset
        Reset a Firewall rule.
        example: ptconfigure firewall reset --firewall-rule="ssh/tcp"

        - default
        Set default policy, should be allow, deny, or reject
        example: ptconfigure firewall default --policy="deny"

HELPDATA;
      return $help ;
    }

}