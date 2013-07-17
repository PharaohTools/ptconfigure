<?php

Namespace Info;

class JenkinsPluginsInfo extends Base {

    public $hidden = false;

    public $name = "JenkinsPlugins";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "JenkinsPlugins" =>  array_merge(parent::defaultActionsAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("jenkinsplugins"=>"JenkinsPlugins", "jenkins-plugins"=>"JenkinsPlugins",
        "jenkins-plugs"=>"JenkinsPlugins");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install a bunch of plugins that we recommend for
  PHP builds in Jenkins.

  JenkinsPlugins, jenkinsplugins, jenkins-plugins, jenkins-plugs

        - install
        Installs the latest version of Jenkins Plugins for PHP recommended by Golden Contact
        example: cleopatra jenkins-plugins install

HELPDATA;
      return $help ;
    }

}