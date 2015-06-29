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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a tiny set of environments"),),),

                // Bastion
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a Bastion Box" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => $bastion_env,
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => $bastion_env,
                    "provider-name" => $provider,
                    "box-amount" => $box_amount_bastion ,
                    "image-id" => $image_id_bastion ,
                    "region-id" => $region_id_bastion ,
                    "size-id" => $size_id_bastion ,
                    "server-prefix" => $prefix,
                    "box-user-name" => $user_name_bastion ,
                    "ssh-key-name" => "$ssh_key_name",
                    "private-ssh-key-path" => $priv_ssh_key_bastion,
                    "wait-for-box-info" => true,
                    "wait-until-active" => true,
                ),),),

                // Git
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a GitBucket Box" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => $git_env,
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => $git_env,
                    "provider-name" => $provider,
                    "box-amount" => $box_amount_git ,
                    "image-id" => $image_id_git ,
                    "region-id" => $region_id_git ,
                    "size-id" => $size_id_git ,
                    "server-prefix" => $prefix,
                    "box-user-name" => $user_name_git ,
                    "ssh-key-name" => "$ssh_key_name",
                    "private-ssh-key-path" => $priv_ssh_key_git ,
                    "wait-for-box-info" => true,
                    "wait-until-active" => true,
                ),),),

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
                    "ssh-key-name" => "$ssh_key_name",
                    "private-ssh-key-path" => $priv_ssh_key_build ,
                    "wait-for-box-info" => true,
                    "wait-until-active" => true,
                ),),),

                // Staging
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a Staging Box" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => $staging_env,
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => $staging_env,
                    "provider-name" => $provider,
                    "box-amount" => $box_amount_staging ,
                    "image-id" => $image_id_staging ,
                    "region-id" => $region_id_staging ,
                    "size-id" => $size_id_staging ,
                    "server-prefix" => $prefix,
                    "box-user-name" => $user_name_staging ,
                    "ssh-key-name" => "$ssh_key_name",
                    "private-ssh-key-path" => $priv_ssh_key_staging ,
                    "wait-for-box-info" => true,
                    "wait-until-active" => true,
                ),),),

                // Production
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a Production Box" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => $production_env,
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => $production_env,
                    "provider-name" => $provider,
                    "box-amount" => $box_amount_production ,
                    "image-id" => $image_id_production ,
                    "region-id" => $region_id_production ,
                    "size-id" => $size_id_production ,
                    "server-prefix" => $prefix,
                    "box-user-name" => $user_name_production ,
                    "ssh-key-name" => "$ssh_key_name",
                    "private-ssh-key-path" => $priv_ssh_key_production ,
                    "wait-for-box-info" => true,
                    "wait-until-active" => true,
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a tiny set of environments complete"),),),

        );

    }

}
