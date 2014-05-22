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
                array ( "Autopilot" => array("execute" => array(
                    "autopilot-file" => "{$parent}build/config/cleopatra/autopilots/medium-prod-db-secondary-prep-ubuntu.php",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Cleo and Dapper on the Production Secondary DB Environment" ),),),
                array ( "Autopilot" => array("execute" => array(
                    "autopilot-file" => "{$parent}build/config/cleopatra/autopilots/medium-prod-db-secondary-invoke-cleo-dapper-new.php",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Production Secondary DB Box on the Production Secondary DB Environment" ),),),
                array ( "Autopilot" => array("execute" => array(
                    "autopilot-file" => "{$parent}build/config/cleopatra/autopilots/medium-prod-db-secondary-invoke-db-node.php",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Production Secondary DB environment complete"),),),
                // DB Nodes should be done first, so the Manager node can start the completed cluster
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Manage Configuration on the Production Primary DB Environment" ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Prep Ubuntu on the Production Primary DB Environment" ),),),
                array ( "Autopilot" => array("execute" => array(
                    "autopilot-file" => "{$parent}build/config/cleopatra/autopilots/medium-prod-web-nodes-prep-ubuntu.php",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Cleo and Dapper on the Production Primary DB Environment" ),),),
                array ( "Autopilot" => array("execute" => array(
                    "autopilot-file" => "{$parent}build/config/cleopatra/autopilots/medium-prod-web-nodes-invoke-cleo-dapper-new.php",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Production Primary DB Box on the Production Primary DB Environment" ),),),
                array ( "Autopilot" => array("execute" => array(
                    "autopilot-file" => "{$parent}build/config/cleopatra/autopilots/medium-prod-db-primary-invoke-db-primary.php",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Production Primary DB environment complete"),),),
            );

    }

}
