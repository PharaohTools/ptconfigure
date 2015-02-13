<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;

    public function __construct() {
        $this->setSteps();
    }

    /* Steps */
    private function setSteps() {

        include("settings.php") ;

        $this->steps =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Creating a Database Cluster of environments"),),),

                // Bastion
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add all Boxes and Environments in Parallel" ),),),
                array ( "Parallax" => array("cli" => array(
                    "command-1" => "ptconfigure autopilot execute --autopilot-file=\"{$parent}boxify-add-db-load-balancer.php\"",
                    "command-2" => "ptconfigure autopilot execute --autopilot-file=\"{$parent}boxify-add-db-nodes.php\"",
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Creating a Database Cluster of environments complete"),),),

            );

    }

}
