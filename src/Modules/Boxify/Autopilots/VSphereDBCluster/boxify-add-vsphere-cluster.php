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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin creating boxes for a VSphere Cluster"),),),

                // Bastion
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add all Boxes and Environments in Parallel" ),),),
                array ( "Parallax" => array("cli" => array(
                    "command-1" => "cleopatra autopilot execute --autopilot-file=\"{$parent}boxify-add-db-nodes.php\"",
                    "command-2" => "cleopatra autopilot execute --autopilot-file=\"{$parent}boxify-add-db-load-balancer.php\"",
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Creating boxes for a VSphere cluster complete"),),),

            );

    }

}
