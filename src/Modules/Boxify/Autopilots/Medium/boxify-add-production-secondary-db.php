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
                // Production Secondary DB
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add the Production Secondary DB Environment" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "medium-prod-secondary-db",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add the Production Secondary DB Box" ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "medium-prod-secondary-db",
                    "provider-name" => "$provider_secondary_db",
                    "box-amount" => "$box_amount_secondary_db",
                    "image-id" => "$image_id_secondary_db",
                    "region-id" => "$region_id_secondary_db",
                    "size-id" => "$size_id_secondary_db",
                    "server-prefix" => $prefix,
                    "box-user-name" => "$user_name_secondary_db",
                    "private-ssh-key-path" => "$priv_ssh_key_secondary_db",
                    "wait-for-box-info" => true,
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Creating medium-prod-secondary-db environment complete"),),),

            );

    }

}
