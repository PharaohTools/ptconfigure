<?php

Namespace Info;

class LDAPToolsInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "LDAP Tools - Tools for working with LDAP Directory Management";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "LDAPTools" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("LDAPTools"=>"LDAPTools", "ldaptools"=>"LDAPTools", "ldap-tools"=>"LDAPTools");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to install the slapd LDAP Server and a set of common LDAP Tools.

  LDAPTools, ldap-tools, ldaptools

        - install
        Installs the latest version of LDAP Tools
        example: ptconfigure ldaptools install

HELPDATA;
      return $help ;
    }

}