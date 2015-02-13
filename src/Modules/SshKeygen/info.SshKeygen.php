<?php

Namespace Info;

class SshKeygenInfo extends PTConfigureBase {

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
        example: ptconfigure ssh-keygen install
        example: ptconfigure ssh-keygen install --yes --bits=4096 --type=rsa --path="/home/dave/.ssh/id_rsa" --comment="Daves"

        - uninstall
        Removes an SSH Key
        example: ptconfigure ssh-keygen uninstall

HELPDATA;
    return $help ;
  }

}