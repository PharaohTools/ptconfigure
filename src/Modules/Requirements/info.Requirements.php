<?php

Namespace Info;

class RequirementsInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Requirements Functionality";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Requirements" => array_merge(array("help", "run", "install") ) );
    }

    public function routeAliases() {
      return array("req" => "Requirements", "requirements-install" => "Requirements",
          "requirements" => "Requirements");
    }

  public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module handles Running methods from Other Pharaoh Tools.

  requirements, req, requirements-install

        - run
        Will roleexecution a file or directory from one location to another
        example: ptconfigure req run
        example: ptconfigure req run -yg --tool=
                    --module={Module Name}
                    --action={Action Name}
                    --params="param1:value1,param2:value2"

HELPDATA;
      return $help ;
    }

}