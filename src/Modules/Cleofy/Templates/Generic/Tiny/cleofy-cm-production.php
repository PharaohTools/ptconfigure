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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Manage Configuration on the Production Environment" ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Ensure PHP and Git on the Production Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'ptconfigure autopilot execute --autopilot-file="build'.DS.'config'.DS.'ptconfigure'.DS.'cleofy'.DS.$production_env.'-prep-linux.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Pharaoh Configure and Pharaoh Deploy on the Production Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'ptconfigure autopilot execute --autopilot-file="build'.DS.'config'.DS.'ptconfigure'.DS.'cleofy'.DS.$production_env.'-invoke-ptc-ptd.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup a Standalone Server Box on the Production Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'ptconfigure autopilot execute --autopilot-file="build'.DS.'config'.DS.'ptconfigure'.DS.'cleofy'.DS.$production_env.'-invoke-standalone-server.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Production Environment complete"),),),
            );

    }

}
