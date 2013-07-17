<?php

Namespace Info;

class JenkinsInfo extends Base {

    public $hidden = false;

    public $name = "Jenkins";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Jenkins" =>  array_merge(parent::defaultActionsAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("jenkins"=>"Jenkins");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install Jenkins, the popular Build Server.

  Jenkins, jenkins

        - install
        Installs Jenkins through apt-get
        example: cleopatra jenkins install

HELPDATA;
      return $help ;
    }

}