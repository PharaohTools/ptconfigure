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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add all DNSes and Environments in Parallel" ),),),
                array ( "Parallax" => array("cli" => array(
                    "command-1"  => "ptconfigure autopilot execute {$parent}dnsify-destroy-bastion.php",
                    "command-2"  => "ptconfigure autopilot execute {$parent}dnsify-destroy-git.php",
                    "command-3"  => "ptconfigure autopilot execute {$parent}dnsify-destroy-jenkins.php",
                    "command-4"  => "ptconfigure autopilot execute {$parent}dnsify-destroy-staging-web-nodes.php",
                    "command-5"  => "ptconfigure autopilot execute {$parent}dnsify-destroy-staging-load-balancer.php",
                    "command-6"  => "ptconfigure autopilot execute {$parent}dnsify-destroy-staging-db-nodes.php",
                    "command-7"  => "ptconfigure autopilot execute {$parent}dnsify-destroy-staging-db-balancer.php",
                    "command-8"  => "ptconfigure autopilot execute {$parent}dnsify-destroy-production-web-nodes.php",
                    "command-9"  => "ptconfigure autopilot execute {$parent}dnsify-destroy-production-load-balancer.php",
                    "command-10" => "ptconfigure autopilot execute {$parent}dnsify-destroy-production-db-nodes.php",
                    "command-11" => "ptconfigure autopilot execute {$parent}dnsify-destroy-production-db-balancer.php",
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Creating a medium set of environments complete"),),),

            );

    }

}
