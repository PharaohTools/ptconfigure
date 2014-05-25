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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Manage Configuration on the Production Secondary DB Environment" ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Prep Ubuntu on the Production Secondary DB Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-prod-secondary-db-prep-ubuntu.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Cleo and Dapper on the Production Secondary DB Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-prod-secondary-db-invoke-cleo-dapper-new.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Production Secondary DB Box on the Production Secondary DB Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-prod-secondary-db-invoke-db-node.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Production Secondary DB environment complete"),),),
                // DB Nodes should be done first, so the Manager node can start the completed cluster
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Manage Configuration on the Production Primary DB Environment" ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Prep Ubuntu on the Production Primary DB Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-prod-primary-db-prep-ubuntu.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Cleo and Dapper on the Production Primary DB Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-prod-primary-db-invoke-cleo-dapper-new.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Production Primary DB Box on the Production Primary DB Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/medium-prod-primary-db-invoke-db-primary.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Production Primary DB environment complete"),),),
            );

    }

}
