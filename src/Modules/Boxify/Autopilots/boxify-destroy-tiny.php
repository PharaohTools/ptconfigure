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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Bastion Box" ),),),
                array ( "Boxify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "tiny-bastion",
                    "provider-name" => "DigitalOcean",
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "tiny-bastion"
                ),),),

                // Jenkins
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Jenkins" ),),),
                array ( "Boxify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "tiny-jenkins",
                    "provider-name" => "DigitalOcean",
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "tiny-jenkins"
                ),),),

                // Staging
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Staging Box" ),),),
                array ( "Boxify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "tiny-staging",
                    "provider-name" => "DigitalOcean",
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "tiny-staging"
                ),),),

                // Production
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Production Box" ),),),
                array ( "Boxify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "tiny-prod",
                    "provider-name" => "DigitalOcean",
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "tiny-prod"
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a tiny set of environments complete"),),),

        );

    }

}
