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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a medium set of Web Only environments"),),),

                // Bastion
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add all Boxes and Environments in Parallel" ),),),
                array ( "Parallax" => array("cli" => array(
                    "command-1"  => "cleopatra autopilot execute --autopilot-file=\"{$parent}cleofy-cm-bastion.php\"",
                    "command-2"  => "cleopatra autopilot execute --autopilot-file=\"{$parent}cleofy-cm-git.php\"",
                    "command-3"  => "cleopatra autopilot execute --autopilot-file=\"{$parent}cleofy-cm-jenkins.php\"",
                    "command-4"  => "cleopatra autopilot execute --autopilot-file=\"{$parent}cleofy-cm-staging-web.php\"",
                    "command-5"  => "cleopatra autopilot execute --autopilot-file=\"{$parent}cleofy-cm-production-web.php\"",
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a medium set of Web Only environments complete"),),),

            );

    }

}
