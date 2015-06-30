<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;

    public function __construct() {
        $this->setSteps();
    }

    /* Steps */
    private function setSteps() {

        include(dirname(__DIR__)).DS."settings.php"  ;

        $this->steps =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Manage Configuration on the Bastion Environment" ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Prep Ubuntu on the Bastion Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'ptconfigure autopilot execute --autopilot-file="build'.DS.'config'.DS.'ptconfigure'.DS.'cleofy'.DS.$bastion_env.'-prep-linux.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Pharaoh Configure and Pharaoh Deploy on the Bastion Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'ptconfigure autopilot execute --autopilot-file="build'.DS.'config'.DS.'ptconfigure'.DS.'cleofy'.DS.$bastion_env.'-invoke-ptc-ptd.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Bastion Box on the Bastion Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'ptconfigure autopilot execute --autopilot-file="build'.DS.'config'.DS.'ptconfigure'.DS.'cleofy'.DS.$bastion_env.'-invoke-bastion.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Bastion environment complete"),),),
            );

    }

}
