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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Manage Configuration on the Staging DB Nodes Environment" ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Prep Ubuntu on the Staging DB Nodes Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-staging-db-nodes-prep-ubuntu.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Cleo and Dapper on the Staging DB Nodes Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-staging-db-nodes-invoke-cleo-dapper-new.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Staging DB Nodes Box on the Staging DB Nodes Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-staging-db-nodes-invoke-db-node.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Staging DB Nodes environment complete"),),),
                // DB Nodes should be done first, so the Manager node can start the completed cluster
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Manage Configuration on the Staging Primary DB Environment" ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Prep Ubuntu on the Staging Primary DB Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-staging-db-balancer-prep-ubuntu.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Cleo and Dapper on the Staging Primary DB Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-staging-db-balancer-invoke-cleo-dapper-new.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Staging Primary DB Box on the Staging Primary DB Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-staging-db-balancer-invoke-db-load-balancer.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Staging Primary DB environment complete"),),),
            );

    }

}
