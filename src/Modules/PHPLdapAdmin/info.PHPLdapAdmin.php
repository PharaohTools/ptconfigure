<?php

Namespace Info;

class PHPLdapAdminInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "PHP LDAP Admin - Install or remove the PHP LDAP Administrator Application";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PHPLdapAdmin" =>  array_merge(parent::routesAvailable(), array("config", "configure", "install") ) );
    }

    public function routeAliases() {
        return array("php-ldap-admin"=>"PHPLdapAdmin", "phpldapadmin"=>"PHPLdapAdmin");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module provides installs HA Proxy Server

  PHPLdapAdmin, php-ldap-admin, phpldapadmin

        - install
        Installs PHP LDAP Admin
        example: ptconfigure php-ldap-admin install

        - configure, config
        Configure PHP LDAP Admin Settings
        example: ptconfigure php-ldap-admin configure
                    --with-stats # if this flag is included, include the haproxy stats
                    --environment-name="my-nodes" # your environment containing nodes
                    --template_target_port #
                    --listen_ip_port="" # ip and port to listen to

HELPDATA;
      return $help ;
    }

}