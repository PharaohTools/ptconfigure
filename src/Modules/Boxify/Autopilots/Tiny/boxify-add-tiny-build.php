<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;

    public function __construct() {
        $this->setSteps();
    }

    /* Steps */
    private function setSteps() {

        include(dirname(__DIR__)).DS."settings.php" ;

        $this->steps =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of Jenkins in a tiny set of environments"),),),

                // Jenkins
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a Jenkins Box" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => $build_env,
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => $build_env,
                    "provider-name" => $provider,
                    "box-amount" => $box_amount_build ,
                    "image-id" => $image_id_build ,
                    "region-id" => $region_id_build ,
                    "size-id" => $size_id_build ,
                    "server-prefix" => $prefix,
                    "box-user-name" => $user_name_build ,
                    "ssh-key-name" => $ssh_key_name,
                    "private-ssh-key-path" => $priv_ssh_key_build ,
                    "wait-for-box-info" => true,
                    "wait-until-active" => true,
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a tiny set of environments complete"),),),

        );

    }

}
