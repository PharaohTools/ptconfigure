<?php

Namespace Info;

class GitCommandInfo extends Base {

    public $hidden = false;

    public $name = "Git Commands";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "GitCommand" => array_merge(parent::routesAvailable(), array(
          "create-checkout-branch", "delete-branch", "ensure-branch", "add", "commit", "push", "pull") ) );
    }

    public function routeAliases() {
      return array("git-command" => "GitCommand", "gitcommand" => "GitCommand");
    }

    public function helpDefinition() {
      $help = "
  This command is handles Git Commands

  Git, GitCommand, git-command, gitcommand

  - create-checkout-branch
      create a new branch
      example: dapperstrano git create-branch --branch=*branchname*

  delete-branch
  ensure-branch
  add
  commit
  push
  pull

" ;
      return $help ;
    }

}