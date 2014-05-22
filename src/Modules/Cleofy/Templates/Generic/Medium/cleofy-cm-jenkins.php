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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Manage Configuration on the Jenkins Environment" ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Prep Ubuntu on the Jenkins Environment" ),),),
                array ( "Autopilot" => array("execute" => array(
                    "autopilot-file" => "{$parent}build/config/cleopatra/autopilots/medium-jenkins-prep-ubuntu.php",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Cleo and Dapper on the Jenkins Environment" ),),),
                array ( "Autopilot" => array("execute" => array(
                    "autopilot-file" => "{$parent}build/config/cleopatra/autopilots/medium-jenkins-invoke-cleo-dapper-new.php",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Jenkins Box on the Jenkins Environment" ),),),
                array ( "Autopilot" => array("execute" => array(
                    "autopilot-file" => "{$parent}build/config/cleopatra/autopilots/medium-jenkins-invoke-build-server.php",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Jenkins environment complete"),),),
            );

    }

}
