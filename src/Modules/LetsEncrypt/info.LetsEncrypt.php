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
            --domain=www.site.com
            --webroot=/var/www/mysite
            --cert-path=/etc/ssl/certificates


';
      return $help ;
    }

}