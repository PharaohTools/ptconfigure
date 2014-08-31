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
                // Git
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add the GitBucket Environment" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "medium-git",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add the GitBucket Box" ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "medium-git",
                    "provider-name" => "$provider_git",
                    "box-amount" => "$box_amount_git",
                    "image-id" => "$image_id_git",
                    "region-id" => "$region_id_git",
                    "size-id" => "$size_id_git",
                    "server-prefix" => $prefix,
                    "box-user-name" => "$user_name_git",
                    "ssh-key-name" => "$ssh_key_name",
                    "private-ssh-key-path" => "$priv_ssh_key_git",
                    "wait-for-box-info" => true,
                    "max-box-info-wait-time" => $wait_time,
                    "wait-until-active" => true,
                    "max-active-wait-time" => $wait_time,
                    "parallax" => true
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Creating medium-git environment complete"),),),
            );

    }

}
