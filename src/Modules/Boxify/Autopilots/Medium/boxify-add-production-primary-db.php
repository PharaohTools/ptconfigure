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
                // Production Primary DB
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add the Production Primary DB Environment" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "medium-prod-primary-db",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add the Production Primary DB Box" ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "medium-prod-primary-db",
                    "provider-name" => "$provider_primary_db",
                    "box-amount" => "$box_amount_primary_db",
                    "image-id" => "$image_id_primary_db",
                    "region-id" => "$region_id_primary_db",
                    "size-id" => "$size_id_primary_db",
                    "server-prefix" => $prefix,
                    "box-user-name" => "$user_name_primary_db",
                    "private-ssh-key-path" => "$priv_ssh_key_primary_db",
                    "wait-for-box-info" => true,
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Creating medium-prod-primary-db environment complete"),),),

            );

    }

}
