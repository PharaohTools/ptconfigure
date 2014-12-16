<?php

Namespace Info;

class GitCommandInfo extends Base {

    public $hidden = false;

    public $name = "Git Commands";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "GitCommand" => array_merge(parent::routesAvailable(), array( "help", "checkout-branch",
          "create-checkout-branch", "delete-branch", "ensure-branch", "ensure=delete-branch", "add", "commit", "push", "pull") ) );
    }

    public function routeAliases() {
      return array("git-command" => "GitCommand", "gitcommand" => "GitCommand");
    }

    public function helpDefinition() {
      $help = "
  This command handles Git Functionality

  Git, GitCommand, git-command, gitcommand

  - checkout-branch
    checkout a branch
    example: ".PHARAOH_APP." git checkout-branch --branch=*branchname*

  - create-checkout-branch
    checkout a branch, creating a new branch if needed
    example: ".PHARAOH_APP." git create-checkout-branch --branch=*branchname*

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