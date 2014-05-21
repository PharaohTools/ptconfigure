<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;

    public function __construct() {
        $this->setSteps();
    }

    /* Steps */
    private function setSteps() {

        include("settings.php") ;

        $this->steps =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a medium set of environments"),),),

                // Bastion
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a Bastion Box" ),),),
                array ( "Parallax" => array("cli" => array(
                    "command-1" => '',
                    "command-1" => '',
                    "command-1" => '',
                    "command-1" => '',
                    "command-1" => '',
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a medium set of environments complete"),),),

            );

    }

}
