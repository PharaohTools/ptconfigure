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


                array ( "Logging" => array( "log" => array( "log-message" => "Lets Manage Configuration on the Staging Web Nodes Environment" ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Prep Ubuntu on the Staging Web Nodes Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-staging-web-nodes-prep-ubuntu.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Cleo and Dapper on the Staging Web Nodes Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-staging-web-nodes-invoke-cleo-dapper-new.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Staging Web Nodes Box on the Staging Web Nodes Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-staging-web-nodes-invoke-web-node.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Staging Web Nodes environment complete"),),),

                // Load balancer should be after web nodes, so we don't try to serve requests from nodes that aren't ready
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Manage Configuration on the Staging Load Balancer Environment" ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Prep Ubuntu on the Staging Load Balancer Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-staging-load-balancer-prep-ubuntu.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Cleo and Dapper on the Staging Load Balancer Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-staging-load-balancer-invoke-cleo-dapper-new.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Staging Load Balancer Box on the Staging Load Balancer Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-staging-load-balancer-invoke-web-load-balancer.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Staging Load Balancer environment complete"),),),
            );

    }

}
