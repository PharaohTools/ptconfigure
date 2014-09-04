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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Creating a medium set of environments"),),),

                // Bastion
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add all DNSes and Environments in Parallel" ),),),
                array ( "Parallax" => array("cli" => array(
                    "command-1"  => "cleopatra autopilot execute --autopilot-file=\"{$parent}dnsify-add-bastion.php\"",
                    "command-2"  => "cleopatra autopilot execute --autopilot-file=\"{$parent}dnsify-add-git.php\"",
                    "command-3"  => "cleopatra autopilot execute --autopilot-file=\"{$parent}dnsify-add-jenkins.php\"",
                    "command-4"  => "cleopatra autopilot execute --autopilot-file=\"{$parent}dnsify-add-staging-web-nodes.php\"",
                    "command-5"  => "cleopatra autopilot execute --autopilot-file=\"{$parent}dnsify-add-staging-load-balancer.php\"",
                    "command-6"  => "cleopatra autopilot execute --autopilot-file=\"{$parent}dnsify-add-staging-secondary-db.php\"",
                    "command-7"  => "cleopatra autopilot execute --autopilot-file=\"{$parent}dnsify-add-staging-primary-db.php\"",
                    "command-8"  => "cleopatra autopilot execute --autopilot-file=\"{$parent}dnsify-add-production-web-nodes.php\"",
                    "command-9"  => "cleopatra autopilot execute --autopilot-file=\"{$parent}dnsify-add-production-load-balancer.php\"",
                    "command-10" => "cleopatra autopilot execute --autopilot-file=\"{$parent}dnsify-add-production-secondary-db.php\"",
                    "command-11" => "cleopatra autopilot execute --autopilot-file=\"{$parent}dnsify-add-production-primary-db.php\"",
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Creating a medium set of environments complete"),),),

            );

    }

}
