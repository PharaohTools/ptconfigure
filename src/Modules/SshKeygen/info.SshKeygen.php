<?php

Namespace Info;

class SshKeygenInfo extends CleopatraBase {

  public $hidden = false;

  public $name = "SSH Keygen - Generate SSH Kay Pairs";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "SshKeygen" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("sshkeygen"=>"SshKeygen", "ssh-keygen"=>"SshKeygen");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to install an SSH Key Pair.

  SshKeygen, ssh-keygen, sshkeygen

        - install
        Installs a new SSH Key
        example: cleopatra ssh-keygen install
        example: cleopatra ssh-keygen install --yes --ssh-keygen-bits=4096 --ssh-keygen-type=rsa --ssh-keygen-path="/home/dave/.ssh/id_rsa" --ssh-keygen-comment="Daves"

        - uninstall
        Removes an SSH Key
        example: cleopatra ssh-keygen uninstall

HELPDATA;
    return $help ;
  }

}