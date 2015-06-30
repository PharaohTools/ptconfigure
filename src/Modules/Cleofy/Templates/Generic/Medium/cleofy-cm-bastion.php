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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Prep Ubuntu on the Bastion Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'ptconfigure autopilot execute --autopilot-file="build/config/ptconfigure/cleofy/medium-bastion-prep-ubuntu.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Pharaoh Configure and Pharaoh Deploy on the Bastion Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'ptconfigure autopilot execute --autopilot-file="build/config/ptconfigure/cleofy/medium-bastion-invoke-cleo-dapper-new.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Bastion Box on the Bastion Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'ptconfigure autopilot execute --autopilot-file="build/config/ptconfigure/cleofy/medium-bastion-invoke-bastion.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Bastion environment complete"),),),
            );

    }

}
