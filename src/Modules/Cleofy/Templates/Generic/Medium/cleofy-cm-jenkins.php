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
                    "guess" => true,
                    "command" => 'ptconfigure autopilot execute --autopilot-file="build/config/ptconfigure/cleofy/autopilots/generated/medium-build-prep-ubuntu.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Cleo and Dapper on the Jenkins Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'ptconfigure autopilot execute --autopilot-file="build/config/ptconfigure/cleofy/autopilots/generated/medium-build-invoke-cleo-dapper-new.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Jenkins Box on the Jenkins Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'ptconfigure autopilot execute --autopilot-file="build/config/ptconfigure/cleofy/autopilots/generated/medium-build-invoke-build-server.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Jenkins environment complete"),),),
            );

    }

}
