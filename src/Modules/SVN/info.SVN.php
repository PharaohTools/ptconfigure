<?php

Namespace Info;

class SVNInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "SVN - The Source Control Manager";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "SVN" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("svn"=>"SVN", "subversion"=>"SVN");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to install the latest available SVN in the Ubuntu
  repositories. Also handles SVN Checkout Functions.

  SVN, svn

        - install
        Installs the latest available (In your package manager) version of SVN
        example: ptconfigure svn install

        - ensure
        Ensures SVN is installed
        example: ptconfigure svn ensure

        - uninstall
        Installs the latest version of SVN
        example: ptconfigure svn uninstall


  Checkout, checkout, co

        - perform a checkout into configured projects folder. If you don't want to specify target dir but do want
        to specify a branch, then enter the text "none" as that parameter.
        example: ptdeploy svn co https://svnhub.com/phpengine/yourmum {optional target dir} {optional branch}
        example: ptdeploy svn co https://svnhub.com/phpengine/yourmum none {optional branch}

HELPDATA;
      return $help ;
    }

}