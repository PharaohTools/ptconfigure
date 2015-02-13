<?php

Namespace Info;

class ParallelSshChildInfo extends Base {

    public $hidden = false;

    public $name = "Command Execution Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "ParallelSshChild" => array_merge(parent::routesAvailable(), array("execute") ) );
    }

    public function routeAliases() {
      return array("parallel-ssh-child"=>"ParallelSshChild", "parallelsshchild"=>"ParallelSshChild");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of a default Module and handles Command Execution Functions. It is unlikely you'll need to manually
  execute this command, it is used by Invoke and Parallax to spawn processes and store execution output in a Parallax friendly
  file.

  ParallelSshChild, parallel-ssh-child, parallelsshchild

          - execute
          execute a single command
          example: ptdeploy parallel-ssh-child execute

HELPDATA;
      return $help ;
    }

}