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

                array ( "Logging" => array( "log" => array( "log-message" => "Lets Manage Configuration on the Production Web Nodes Environment" ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Prep Ubuntu on the Production Web Nodes Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-prod-web-nodes-prep-ubuntu.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Cleo and Dapper on the Production Web Nodes Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-prod-web-nodes-invoke-cleo-dapper-new.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Production Web Nodes Box on the Production Web Nodes Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-prod-web-nodes-invoke-web-node.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Production Web Nodes environment complete"),),),

                // Load balancer should be after web nodes, so we don't try to serve requests from nodes that aren't ready
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Manage Configuration on the Production Load Balancer Environment" ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Prep Ubuntu on the Production Load Balancer Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-prod-load-balancer-prep-ubuntu.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Cleo and Dapper on the Production Load Balancer Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-prod-load-balancer-invoke-cleo-dapper-new.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Production Load Balancer Box on the Production Load Balancer Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-prod-load-balancer-invoke-load-balancer.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Production Load Balancer environment complete"),),),
            );

    }

}
