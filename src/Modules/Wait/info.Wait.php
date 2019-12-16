<?php

Namespace Info;

class WaitInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Wait Functionality";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Wait" => array_merge(parent::routesAvailable(), array("time") ) );
    }

    public function routeAliases() {
      return array("wait" => "Wait");
    }

  public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module handles Waiting for a defined time

  Wait, wait

        - time
        Will wait for a specified period of time
        example: ptconfigure wait time
        example: ptconfigure wait time -yg # will guess 10 seconds
        example: ptconfigure wait time -yg --seconds="10"

HELPDATA;
      return $help ;
    }

}