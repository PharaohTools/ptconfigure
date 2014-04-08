<?php

Namespace Info;

class GitToolsInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Git Tools - Tools for working with Git SCM";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "GitTools" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("gittools"=>"GitTools", "git-tools"=>"GitTools");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install Git and a set of common Git Tools. These include
  Git - the distributed source control manager, git Core a supplement to Git, Gitk
  which is a GUI for git, and git-cola, which is also a Git GUI.

  GitTools, gittools, git-tools

        - install
        Installs the latest version of Git Tools
        example: cleopatra gittools install

HELPDATA;
      return $help ;
    }

}