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
            array("help", "status", "install", "enable", "reload", "disable", "allow", "deny", "reject", "limit",
                "delete", "insert", "reset")
        ) );
    }

    public function routeAliases() {
      return array("firewall"=>"Firewall");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to modify create or modify firewalls

  Firewall, firewall

        - enable
        Enable system firewall
        example: ptconfigure firewall enable

        - reload
        Reload system firewall with new configuration settings
        example: ptconfigure firewall reload

        - disable
        Disable system firewall
        example: ptconfigure firewall disable

        - allow
        Allow a Firewall rule
        example: ptconfigure firewall allow --port="ssh/tcp"

        - deny
        Deny a Firewall rule. Allow connection attempts to be ignored and time out.
        example: ptconfigure firewall deny --port="ssh/tcp"

        - reject (Automatically Denies for Redhat based systems)
        Reject a Firewall rule. Terminate connections attempts with an error to the connector.
        example: ptconfigure firewall reject --port="ssh/tcp"

        - limit
        Limit a Firewall rule. ufw will deny connections if an IP address has attempted
        to initiate 6 or more connections in the last 30 seconds.
        example: ptconfigure firewall limit --port="ssh/tcp"

        - delete (Irrelevant for Redhat based systems)
        Delete a Firewall rule.
        example: ptconfigure firewall delete --port="ssh/tcp"

        - insert (Irrelevant for Redhat based systems)
        Insert a Firewall rule.
        example: ptconfigure firewall insert --port="ssh/tcp"

        - reset (Irrelevant for Redhat based systems)
        Reset a Firewall rule.
        example: ptconfigure firewall reset --port="ssh/tcp"

        - default
        Set default policy, should be allow, deny, or reject
        example: ptconfigure firewall default --policy="deny"

HELPDATA;
      return $help ;
    }

}