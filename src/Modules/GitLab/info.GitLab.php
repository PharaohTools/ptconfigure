<?php

Namespace Info;

class GitLabInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Git Lab - The Git SCM Management Web Application";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "GitLab" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("gitlab"=>"GitLab", "git-lab"=>"GitLab");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install a full Git Lab installation on to a server
  The dependencies for GitLab are also installed.

  GitLab, gitlab, git-lab

        - install
        Installs the latest version of GitLab on a system
        example: cleopatra gitlab install

HELPDATA;
      return $help ;
    }

}