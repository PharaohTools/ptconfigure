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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Manage Configuration on the Bastion Environment" ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "do pwd ".getcwd() ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'pwd',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
//                array ( "Logging" => array( "log" => array( "log-message" => "do cd" ),),),
//                array ( "RunCommand" => array("install" => array(
//                    "command" => "cd $parent",
//                    "run-as-user" => "",
//                    "background" => "",
//                ),),),
//                array ( "Logging" => array( "log" => array( "log-message" => "do pwd" ),),),
//                array ( "RunCommand" => array("install" => array(
//                    "command" => 'pwd',
//                    "run-as-user" => "",
//                    "background" => "",
//                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Prep Ubuntu on the Bastion Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/autopilots/medium-bastion-prep-ubuntu.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Cleo and Dapper on the Bastion Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/autopilots/medium-bastion-invoke-cleo-dapper-new.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Bastion Box on the Bastion Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "command" => 'cleopatra autopilot execute --autopilot-file="build/config/cleopatra/autopilots/medium-bastion-invoke-bastion.php"',
                    "run-as-user" => "",
                    "background" => "",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Bastion environment complete"),),),
            );

    }

}
