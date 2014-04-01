<?php

Namespace Info;

class BoxManagerInfo extends Base {

  public $hidden = false;

  public $name = "Native Box Manager Wrapper - Install OS neutral environments";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "BoxManager" =>  array_merge(parent::routesAvailable(), array("box-add", "box-remove") ) );
  }

  public function routeAliases() {
    return array("box-manager"=>"BoxManager", "boxmanager"=>"BoxManager", "box-mgr"=>"BoxManager",
        "boxmgr"=>"BoxManager");
  }

  public function autoPilotVariables() {
    return array(
      "BoxManager" => array(
        "BoxManager" => array(
          "programDataFolder" => "/opt/BoxManager", // command and app dir name
          "programNameMachine" => "environmentmanager", // command and app dir name
          "programNameFriendly" => "Box Mgr.", // 12 chars
          "programNameInstaller" => "Native Box Manager Wrapper",
        ),
      )
    );
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to use a Box Management wrapper.

  BoxManager, environment-manager, environmentmanager, environment-mgr, boxmgr

        - box-add
        Installs a Box through a Box Manager
        example: cleopatra environment-manager install --environment-name="mysql" --environment-version="5.0" --provider="apt-get"

        - box-remove
        Removes a Box through a Box Manager
        example: cleopatra environment-manager install --environment-name="mysql" --environment-version="5.0" --provider="apt-get"

  A environment manager wrapper that will allow you to install environments on any system

HELPDATA;
    return $help ;
  }

}