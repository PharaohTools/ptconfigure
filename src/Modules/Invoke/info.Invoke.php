<?php

Namespace Info;

class InvokeInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "SSH Invocation Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Invoke" => array_merge(parent::routesAvailable(), array("cli", "script", "data") ) );
    }

    public function routeAliases() {
      return array("invoke" => "Invoke", "inv" => "Invoke");
    }

  public function helpDefinition() {
      $help = '
  This command is part of the Default Distribution and handles SSH Connection Functions.

  Invoke, invoke, inv

        - cli
        Will ask you for details for servers, then open a shell for you to execute on multiple servers
        example: '.PHARAOH_APP.' invoke cli -yg
            --env=staging # environment name to connect to
            --driver=seclib # optional ssh client driver to choose (seclib/native/os)
            --timeout=30 # will guess 30 seconds to wait for connections

        - script
        Will ask you for details for servers, then execute each line of a provided script file on the remote/s
        example: '.PHARAOH_APP.' invoke script -yg
            --ssh-script="/var/www/project/script.sh"
            --env=staging
            --driver=seclib # optional ssh client driver to choose (seclib/native/os)
            --timeout=30 # will guess 30 seconds to wait for connections

        - data
        Execute php variable data on one or more remotes
        example: '.PHARAOH_APP.' invoke data -yg
            --ssh-data="ls -lah"
            --env=staging
            --driver=seclib # optional ssh client driver to choose (seclib/native/os)
            --timeout=30 # will guess 30 seconds to wait for connections

';
      return $help ;
    }

}