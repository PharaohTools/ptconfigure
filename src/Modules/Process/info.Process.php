<?php

Namespace Info;

class ProcessInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Process Functionality";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Process" => array_merge(parent::routesAvailable(), array("kill") ) );
    }

    public function routeAliases() {
      return array("process" => "Process");
    }

  public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command handles process functions, kill a process for now

  Process, process

        - kill
        Will ask you for process name, aa file or directory from local to remote
        example: cleopatra process kill
        example: cleopatra process kill --yes --name="selenium" --use-psax # default, will look for string in result of
        example: cleopatra process kill --yes --name="selenium" --use-pkill # will allow pkill to find  by string to kill
        example: cleopatra process kill --yes
                                        --guess
                                        --id="1234 # will kill a process by id
                                        --level # will guess a 9

HELPDATA;
      return $help ;
    }

}