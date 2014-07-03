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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a Bastion Server in a  tiny set of environments"),),),

                // Bastion
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a Bastion Box" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "tiny-bastion",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "tiny-bastion",
                    "provider-name" => $provider,
                    "box-amount" => $box_amount_bastion ,
                    "image-id" => $image_id_bastion ,
                    "region-id" => $region_id_bastion ,
                    "size-id" => $size_id_bastion ,
                    "server-prefix" => $prefix,
                    "box-user-name" => $box_user_name_bastion ,
                    "private-ssh-key-path" => $priv_ssh_key_bastion,
                    "wait-for-box-info" => true,
                    "wait-until-active" => true,
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a tiny set of environments complete"),),),

        );

    }

}
