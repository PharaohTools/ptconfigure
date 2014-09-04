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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a Gitbucket Box in a tiny set of environments"),),),

                // Git
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a GitBucket Box" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "tiny-git",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "tiny-git",
                    "provider-name" => $provider,
                    "box-amount" => $box_amount_git ,
                    "image-id" => $image_id_git ,
                    "region-id" => $region_id_git ,
                    "size-id" => $size_id_git ,
                    "server-prefix" => $prefix,
                    "box-user-name" => $user_name_git ,
                    "private-ssh-key-path" => $priv_ssh_key_git ,
                    "wait-for-box-info" => true,
                    "wait-until-active" => true,
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a tiny set of environments complete"),),),

        );

    }

}
