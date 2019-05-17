<?php

Namespace Info;

class LetsEncryptInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "SSH Invocation Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "LetsEncrypt" => array_merge(parent::routesAvailable(), array("sign") ) );
    }

    public function routeAliases() {
      return array(
          "LetsEncrypt" => "LetsEncrypt", "letsencrypt" => "LetsEncrypt", "lets-encrypt" => "LetsEncrypt",
          "lenc" => "LetsEncrypt");
    }

  public function helpDefinition() {
      $help = '
  This module is part of the Default Distribution and handles Lets Encrypt SSL Integration

  LetsEncrypt, LetsEncrypt, letsencrypt, lets-encrypt, lenc

        - sign
        Will ask you for details for servers, then open a shell for you to execute on multiple servers
        example: '.PHARAOH_APP.' LetsEncrypt sign -yg
            --domain=www.site.com # Domain you are creating the certificate for
            --webroot=/var/www/html # Your webroot to allow creation of file to be checked by LE 
            --cert-path=/etc/ssl/certificates # Where to store the Generated Certificate
            --wait=15 # optional, will wait before execution if you need a web server to be ready first
            --email="devops@pharaohtools.com" # Email
            --country="United Kingdom" # Your Country
            --state_or_province="England" # State or Province
            --locality="London" # Your Locality
            --organization="Pharaoh Tools" # Your Organization
            --organizational_unit="DevOps" # Your Organizational Unit
            --street="Buckingham Palace Road" # Where your business lives ;

';
      return $help ;
    }

}