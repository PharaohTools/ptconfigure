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
                // Staging Web Nodes
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add the Staging Web Nodes Environment" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "medium-staging-web-nodes",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add the Staging Web Nodes Boxes" ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "medium-staging-web-nodes",
                    "provider-name" => "$provider_web_nodes",
                    "box-amount" => "$box_amount_web_nodes",
                    "image-id" => "$image_id_web_nodes",
                    "region-id" => "$region_id_web_nodes",
                    "size-id" => "$size_id_web_nodes",
                    "server-prefix" => $prefix,
                    "box-user-name" => "$user_name_web_nodes",
                    "ssh-key-name" => "$ssh_key_name",
                    "private-ssh-key-path" => "$priv_ssh_key_web_nodes",
                    "wait-for-box-info" => true,
                    "max-box-info-wait-time" => $wait_time,
                    "wait-until-active" => true,
                    "max-active-wait-time" => $wait_time,
                    "parallax" => true
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Creating medium-staging-web-nodes environment complete"),),),

            );

    }

}
