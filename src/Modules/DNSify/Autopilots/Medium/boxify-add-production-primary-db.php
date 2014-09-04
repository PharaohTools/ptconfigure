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
                    "environment-name" => "medium-prod-db-balancer",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add the Production Primary DB Box" ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "medium-prod-db-balancer",
                    "provider-name" => "$provider_db_balancer",
                    "box-amount" => "$box_amount_db_balancer",
                    "image-id" => "$image_id_db_balancer",
                    "region-id" => "$region_id_db_balancer",
                    "size-id" => "$size_id_db_balancer",
                    "server-prefix" => $prefix,
                    "box-user-name" => "$user_name_db_balancer",
                    "ssh-key-name" => "$ssh_key_name",
                    "private-ssh-key-path" => "$priv_ssh_key_db_balancer",
                    "wait-for-box-info" => true,
                    "max-box-info-wait-time" => $wait_time,
                    "wait-until-active" => true,
                    "max-active-wait-time" => $wait_time,
                    "parallax" => true
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Creating medium-prod-db-balancer environment complete"),),),

            );

    }

}
