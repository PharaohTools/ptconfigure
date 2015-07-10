<?php

Namespace Info;

class SshEncryptInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Install / Decrypt / Encrypt private SSH keys";

    public function __construct() {
        parent::__construct();
    }

    public function routesAvailable() {
        return array( "SshEncrypt" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
        return array("ssh-encrypt"=>"SshEncrypt", "sshencrypt"=>"SshEncrypt");
    }

    public function dependencies() {
        return array("Logging", "Encryption") ;
    }

    public function helpDefinition() {
        $help = <<<"HELPDATA"
    This module allows you to install an encrypted private SSH key or to encrypt one.

    SshEncrypt, ssh-encrypt, sshencrypt

        - encrypt
        Installs an encrypted SSH Key.
        example: ptconfigure ssh-encrypt install

        - unencrypt
        Installs an encrypted SSH Key.
        example: ptconfigure ssh-encrypt install

HELPDATA;
        return $help ;
    }

}