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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Destroying of a medium set of environments"),),),

                // Bastion
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Bastion Box" ),),),
                array ( "Boxify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "medium-bastion",
                    "provider-name" => "DigitalOcean",
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "medium-bastion"
                ),),),

                // Git
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the GitBucket Box" ),),),
                array ( "Boxify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "medium-git",
                    "provider-name" => "DigitalOcean",
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "medium-git"
                ),),),

                // Jenkins
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Jenkins Box" ),),),
                array ( "Boxify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "medium-jenkins",
                    "provider-name" => "DigitalOcean",
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "medium-jenkins"
                ),),),

                // Staging Web Nodes
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Staging Web Node Boxes" ),),),
                array ( "Boxify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "medium-staging-web-nodes",
                    "provider-name" => "DigitalOcean",
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "medium-staging-web-nodes"
                ),),),

                // Staging Load Balancer
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Staging Load Balancer Box" ),),),
                array ( "Boxify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "medium-staging-load-balancer",
                    "provider-name" => "DigitalOcean",
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "medium-staging-load-balancer"
                ),),),

                // Production Web Nodes
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Production Web Node Boxes" ),),),
                array ( "Boxify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "medium-prod-web-nodes",
                    "provider-name" => "DigitalOcean",
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "medium-prod-web-nodes"
                ),),),

                // Production Load Balancer
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Production Load Balancer Box" ),),),
                array ( "Boxify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "medium-prod-load-balancer",
                    "provider-name" => "DigitalOcean",
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "medium-prod-load-balancer"
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Destroying a medium set of environments complete"),),),

            );

    }

}
