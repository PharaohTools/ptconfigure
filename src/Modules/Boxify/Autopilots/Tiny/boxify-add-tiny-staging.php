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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a Staging Box" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "tiny-staging",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "tiny-staging",
                    "provider-name" => $provider,
                    "box-amount" => $box_amount_staging,
                    "image-id" => $image_id_staging ,
                    "region-id" => $region_id_staging ,
                    "size-id" => $size_id_staging ,
                    "server-prefix" => $prefix,
                    "box-user-name" => $user_name_staging ,
                    "ssh-key-name" => $ssh_key_name_staging,
                    "private-ssh-key-path" => $priv_ssh_key_staging ,
                    "wait-for-box-info" => true,
                    "wait-until-active" => true,
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a tiny set of environments complete"),),),

        );

    }

}
