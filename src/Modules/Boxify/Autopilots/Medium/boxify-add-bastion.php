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
                // Bastion
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add the Bastion Environment" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "medium-bastion",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add the Bastion Box" ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "medium-bastion",
                    "provider-name" => "$provider_bastion",
                    "box-amount" => "$box_amount_bastion",
                    "image-id" => "$image_id_bastion",
                    "region-id" => "$region_id_bastion",
                    "size-id" => "$size_id_bastion",
                    "server-prefix" => $prefix,
                    "box-user-name" => "$user_name_bastion",
                    "ssh-key-name" => "$ssh_key_name",
                    "private-ssh-key-path" => "$priv_ssh_key_bastion",
                    "wait-for-box-info" => true,
                    "max-box-info-wait-time" => $wait_time,
                    "wait-until-active" => true,
                    "max-active-wait-time" => $wait_time,
                    "parallax" => true
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Creating Bastion environment complete"),),),
            );

    }

}
