<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;

    public function __construct() {
        $this->setSteps();
    }

    /* Steps */
    private function setSteps() {

        include ("settings.php") ;

        $this->steps =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Manage Configuration on the Staging Load Balancer Environment" ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Prep Ubuntu on the Staging Load Balancer Environment" ),),),
                array ( "Autopilot" => array("execute" => array(
                    "autopilot-file" => "{$parent}build/config/cleopatra/autopilots/medium-staging-load-balancer-prep-ubuntu.php",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Cleo and Dapper on the Staging Load Balancer Environment" ),),),
                array ( "Autopilot" => array("execute" => array(
                    "autopilot-file" => "{$parent}build/config/cleopatra/autopilots/medium-staging-load-balancer-invoke-cleo-dapper-new.php",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Staging Load Balancer Box on the Staging Load Balancer Environment" ),),),
                array ( "Autopilot" => array("execute" => array(
                    "autopilot-file" => "{$parent}build/config/cleopatra/autopilots/medium-staging-load-balancer-invoke-web-node.php",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Staging Load Balancer environment complete"),),),
            );

    }

}
