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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Creating a medium, web only set of environments"),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Lets add all DNSes and Environments in Parallel" ),),),
                array ( "Parallax" => array("cli" => array(
                    "command-1"  => "ptconfigure autopilot execute --autopilot-file=\"{$parent}dnsify-add-bastion.php\"",
                    "command-2"  => "ptconfigure autopilot execute --autopilot-file=\"{$parent}dnsify-add-git.php\"",
                    "command-3"  => "ptconfigure autopilot execute --autopilot-file=\"{$parent}dnsify-add-jenkins.php\"",
                    "command-4"  => "ptconfigure autopilot execute --autopilot-file=\"{$parent}dnsify-add-staging-web-nodes.php\"",
                    "command-5"  => "ptconfigure autopilot execute --autopilot-file=\"{$parent}dnsify-add-staging-load-balancer.php\"",
                    "command-6"  => "ptconfigure autopilot execute --autopilot-file=\"{$parent}dnsify-add-production-web-nodes.php\"",
                    "command-7"  => "ptconfigure autopilot execute --autopilot-file=\"{$parent}dnsify-add-production-load-balancer.php\"",
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Creating a medium, web only set of environments complete"),),),

            );

    }

}
