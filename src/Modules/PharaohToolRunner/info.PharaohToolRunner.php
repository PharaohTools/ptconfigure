<?php

Namespace Info;

class PharaohToolRunnerInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "PharaohToolRunner Functionality";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "PharaohToolRunner" => array_merge(array("help", "run") ) );
    }

    public function routeAliases() {
      return array("pharaohtoolrunner" => "PharaohToolRunner", "ptrunner" => "PharaohToolRunner",
          "pt-runner" => "PharaohToolRunner");
    }

  public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module handles Running methods from Other Pharaoh Tools.

  PharaohToolRunner, pharaohtoolrunner

        - run
        Will pharaohtoolrunner a file or directory from one location to another
        example: ptconfigure pharaohtoolrunner run
        example: ptconfigure pharaohtoolrunner run
                    --yes
                    --tool={configure,deploy,build or any prefixes}
                    --module={Module Name}
                    --action={Action Name}
                    --params="param1:value1,param2:value2"

HELPDATA;
      return $help ;
    }

}