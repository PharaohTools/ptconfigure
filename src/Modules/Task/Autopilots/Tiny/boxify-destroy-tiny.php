<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;

    public function __construct() {
        $this->setSteps();
    }

    /* Steps */
    private function setSteps() {

        $this->steps =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a tiny set of environments"),),),

                // Bastion
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Bastion DNS" ),),),
                array ( "DNSify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "tiny-bastion",
                    "provider-name" => $provider,
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "tiny-bastion"
                ),),),

                // Git
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the GitBucket DNS" ),),),
                array ( "DNSify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "tiny-git",
                    "provider-name" => $provider,
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "tiny-git"
                ),),),

                // Jenkins
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Jenkins DNS" ),),),
                array ( "DNSify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "tiny-jenkins",
                    "provider-name" => $provider,
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "tiny-jenkins"
                ),),),

                // Staging
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Staging DNS" ),),),
                array ( "DNSify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "tiny-staging",
                    "provider-name" => $provider,
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "tiny-staging"
                ),),),

                // Production
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Production DNS" ),),),
                array ( "DNSify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "tiny-prod",
                    "provider-name" => $provider,
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "tiny-prod"
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a tiny set of environments complete"),),),

        );

    }

}
