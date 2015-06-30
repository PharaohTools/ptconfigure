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


                array ( "Logging" => array( "log" => array( "log-message" => "Lets Manage Configuration on the Staging Web Nodes Environment" ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Ensure PHP and Git on the Staging Web Nodes Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'ptconfigure autopilot execute --autopilot-file="build/config/ptconfigure/cleofy/medium-staging-web-nodes-prep-ubuntu.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Pharaoh Configure and Pharaoh Deploy on the Staging Web Nodes Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'ptconfigure autopilot execute --autopilot-file="build/config/ptconfigure/cleofy/medium-staging-web-nodes-invoke-cleo-dapper-new.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Staging Web Nodes Box on the Staging Web Nodes Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'ptconfigure autopilot execute --autopilot-file="build/config/ptconfigure/cleofy/medium-staging-web-nodes-invoke-web-node.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Staging Web Nodes environment complete"),),),

                // Load balancer should be after web nodes, so we don't try to serve requests from nodes that aren't ready
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Manage Configuration on the Staging Load Balancer Environment" ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Ensure PHP and Git on the Staging Load Balancer Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'ptconfigure autopilot execute --autopilot-file="build/config/ptconfigure/cleofy/medium-staging-load-balancer-prep-ubuntu.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Pharaoh Configure and Pharaoh Deploy on the Staging Load Balancer Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'ptconfigure autopilot execute --autopilot-file="build/config/ptconfigure/cleofy/medium-staging-load-balancer-invoke-cleo-dapper-new.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Staging Load Balancer Box on the Staging Load Balancer Environment" ),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'ptconfigure autopilot execute --autopilot-file="build/config/ptconfigure/cleofy/medium-staging-load-balancer-invoke-web-load-balancer.php"',
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Staging Load Balancer environment complete"),),),
            );

    }

}
