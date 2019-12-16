<?php

Namespace Info;

class RoleExecutionInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "RoleExecution Functionality";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "RoleExecution" => array_merge(array("help", "run", "steps") ) );
    }

    public function routeAliases() {
      return array("role-execution" => "RoleExecution", "rolex" => "RoleExecution",
          "roleexecution" => "RoleExecution");
    }

  public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module handles Running methods from Other Pharaoh Tools.

  RoleExecution, roleexecution, rolex, role-execution

        - run
        Will roleexecution a file or directory from one location to another
        example: ptconfigure rolex run
        example: ptconfigure rolex run -yg 
        example: ptconfigure rolex run -yg --roledir=roles
        example: ptconfigure rolex run -yg --rolefile=roles.yml
        example: ptconfigure rolex run -yg --roledir=roles --rolefile=roles.yml

        - steps
        Will roleexecution a file or directory from one location to another
        example: ptconfigure rolex steps
        example: ptconfigure rolex steps -yg 
        example: ptconfigure rolex steps -yg --roledir=roles
        example: ptconfigure rolex steps -yg --rolefile=steps.yml
        example: ptconfigure rolex steps -yg --roledir=roles --rolefile=steps.yml

HELPDATA;
      return $help ;
    }

}