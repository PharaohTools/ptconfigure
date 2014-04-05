<?php

Namespace Info;

class PHPMDInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "PHP Mess Detector - The static analysis tool";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PHPMD" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("phpmd"=>"PHPMD", "phpmd"=>"PHPMD", "php-md"=>"PHPMD");
    }

    public function autoPilotVariables() {
      return array(
        "PHPMD" => array(
          "PHPMD" => array(
            "programDataFolder" => "/opt/PHPMD", // command and app dir name
            "programNameMachine" => "phpmd", // command and app dir name
            "programNameFriendly" => "PHP MD!", // 12 chars
            "programNameInstaller" => "PHP Mess Detector",
          ),
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to install PHPMD from a GC Repo.

  PHPMD

        - install
        Installs the latest GC Repo version of PHPMD
        example: cleopatra phpmd install

HELPDATA;
      return $help ;
    }

}