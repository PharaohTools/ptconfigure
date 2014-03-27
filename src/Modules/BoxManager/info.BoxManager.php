<?php

Namespace Info;

class BoxManagerInfo extends Base {

  public $hidden = false;

  public $name = "Native Package Manager Wrapper - Install OS neutral packages";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "BoxManager" =>  array_merge(parent::routesAvailable(), array("box-ensure", "box-install", "box-remove") ) );
  }

  public function routeAliases() {
    return array("package-manager"=>"BoxManager", "packagemanager"=>"BoxManager", "package-mgr"=>"BoxManager",
        "boxmgr"=>"BoxManager");
  }

  public function autoPilotVariables() {
    return array(
      "BoxManager" => array(
        "BoxManager" => array(
          "programDataFolder" => "/opt/BoxManager", // command and app dir name
          "programNameMachine" => "packagemanager", // command and app dir name
          "programNameFriendly" => "Package Mgr.", // 12 chars
          "programNameInstaller" => "Native Package Manager Wrapper",
        ),
      )
    );
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This command allows you to use a Package Management wrapper.

  BoxManager, package-manager, packagemanager, package-mgr, boxmgr

        - box-install
        Installs a Package through a Package Manager
        example: cleopatra package-manager install --package-name="mysql" --package-version="5.0" --packager="apt-get"

        - box-ensure
        Installs a Package through a Package Manager
        example: cleopatra package-manager install --package-name="mysql" --package-version="5.0" --packager="apt-get"

        - box-remove
        Removes a Package through a Package Manager
        example: cleopatra package-manager install --package-name="mysql" --package-version="5.0" --packager="apt-get"

  A package manager wrapper that will allow you to install packages on any system

HELPDATA;
    return $help ;
  }

}