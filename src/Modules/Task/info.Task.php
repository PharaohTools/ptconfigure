<?php

Namespace Info;

class TaskInfo extends CleopatraBase {

  public $hidden = false;

  public $name = "Task Wrapper - easily repeatable tasks";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "Task" =>  array_merge(parent::routesAvailable(), array("ensure-domain-exists", "ensure-domain-empty",
        "ensure-record-exists", "ensure-record-empty",) ) );
  }

  public function routeAliases() {
    return array("task"=>"Task");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command provides a generic DNS Management wrapper around all of the DNS Providers (Cloud and Otherwise) so that we have a
  generic way to create and destroy boxes.

  Task, task

        - list
        List all servers in papyrus, or those of a particular environment
        example: cleopatra task list-papyrus --yes
        example: cleopatra task list-papyrus --yes --environment-name="staging"


HELPDATA;
    return $help ;
  }

}