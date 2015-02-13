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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Creating a tiny set of environments"),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Lets add all Boxes and Environments in Parallel" ),),),
                array ( "Parallax" => array("cli" => array(
                    "command-1"  => "ptconfigure autopilot execute --autopilot-file=\"{$parent}boxify-add-tiny-bastion.php\"",
                    "command-2"  => "ptconfigure autopilot execute --autopilot-file=\"{$parent}boxify-add-tiny-git.php\"",
                    "command-3"  => "ptconfigure autopilot execute --autopilot-file=\"{$parent}boxify-add-tiny-jenkins.php\"",
                    "command-4"  => "ptconfigure autopilot execute --autopilot-file=\"{$parent}boxify-add-tiny-staging.php\"",
                    "command-5"  => "ptconfigure autopilot execute --autopilot-file=\"{$parent}boxify-add-tiny-production.php\"",
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Creating a medium, web only set of environments complete"),),),

            );

    }

}
