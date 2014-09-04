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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of Staging Server in a tiny set of environments"),),),

                // Staging
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a Staging DNS" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "tiny-staging",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "DNSify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "tiny-staging",
                    "provider-name" => $provider,
                    "box-amount" => $box_amount_ ,
                    "image-id" => $image_id_ ,
                    "region-id" => $region_id_ ,
                    "size-id" => $size_id_ ,
                    "server-prefix" => $prefix,
                    "box-user-name" => $user_name_ ,
                    "private-ssh-key-path" => $priv_ssh_key_ ,
                    "wait-for-box-info" => true,
                    "wait-until-active" => true,
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a tiny set of environments complete"),),),

        );

    }

}
