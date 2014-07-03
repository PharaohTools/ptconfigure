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
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/tiny-jenkins-prep-ubuntu.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Cleo and Dapper on the Jenkins Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/tiny-jenkins-invoke-cleo-dapper-new.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Jenkins Box on the Jenkins Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/cleofy/autopilots/generated/tiny-jenkins-invoke-build-server.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Jenkins environment complete"),),),
            );

    }

}
