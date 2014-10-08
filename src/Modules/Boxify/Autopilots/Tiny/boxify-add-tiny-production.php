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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of Production Server in a tiny set of environments"),),),

                // Production
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a Production Box" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "tiny-prod",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "tiny-prod",
                    "provider-name" => $provider,
                    "box-amount" => $box_amount_production ,
                    "image-id" => $image_id_production ,
                    "region-id" => $region_id_production ,
                    "size-id" => $size_id_production ,
                    "server-prefix" => $prefix,
                    "box-user-name" => $user_name_production ,
                    "ssh-key-name" => $ssh_key_name,
                    "private-ssh-key-path" => $priv_ssh_key_production ,
                    "wait-for-box-info" => true,
                    "wait-until-active" => true,
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a tiny set of environments complete"),),),

        );

    }

}
