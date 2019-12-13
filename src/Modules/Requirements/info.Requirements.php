<?php

Namespace Info;

class RequirementsInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Requirements Functionality";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Requirements" => array_merge(array("help", "run") ) );
    }

    public function routeAliases() {
      return array("role-execution" => "Requirements", "rolex" => "Requirements",
          "roleexecution" => "Requirements");
    }

  public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module handles Running methods from Other Pharaoh Tools.

  Requirements, roleexecution, rolex, role-execution

        - run
        Will roleexecution a file or directory from one location to another
        example: ptconfigure rolex run
        example: ptconfigure rolex run -yg --tool=
                    --module={Module Name}
                    --action={Action Name}
                    --params="param1:value1,param2:value2"

HELPDATA;
      return $help ;
    }

}