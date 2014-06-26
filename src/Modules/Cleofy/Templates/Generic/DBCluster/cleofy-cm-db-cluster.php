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

                array ( "Logging" => array( "log" => array( "log-message" => "Lets Manage Configuration on the Database Nodes Environment" ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Prep Ubuntu on the Production DB Nodes Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/db-cluster-db-nodes-prep-ubuntu.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Cleo and Dapper on the Production DB Nodes Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/db-cluster-db-nodes-invoke-cleo-dapper-new.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Production DB Nodes Box on the Production DB Nodes Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/db-cluster-db-nodes-invoke-db-node.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Database Nodes environment complete"),),),

                // DB Nodes should be done first, so the Manager node can start the completed cluster
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Manage Configuration on the Database Balancer Environment" ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Prep Ubuntu on the Production Primary DB Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/db-cluster-db-balancer-prep-ubuntu.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Cleo and Dapper on the Production Primary DB Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/db-cluster-db-balancer-invoke-cleo-dapper-new.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Production Primary DB Box on the Production Primary DB Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/db-cluster-db-balancer-invoke-db-load-balancer.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Production Primary DB environment complete"),),),

            );

    }

}
