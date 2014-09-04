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
                // Jenkins
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add the Jenkins Environment" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "medium-jenkins",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add the Jenkins Box" ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "medium-jenkins",
                    "provider-name" => "$provider_jenkins",
                    "box-amount" => "$box_amount_jenkins",
                    "image-id" => "$image_id_jenkins",
                    "region-id" => "$region_id_jenkins",
                    "size-id" => "$size_id_jenkins",
                    "server-prefix" => $prefix,
                    "box-user-name" => "$user_name_jenkins",
                    "ssh-key-name" => "$ssh_key_name",
                    "private-ssh-key-path" => "$priv_ssh_key_jenkins",
                    "wait-for-box-info" => true,
                    "max-box-info-wait-time" => $wait_time,
                    "wait-until-active" => true,
                    "max-active-wait-time" => $wait_time,
                    "parallax" => true
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Creating medium-jenkins environment complete"),),),

            );

    }

}
