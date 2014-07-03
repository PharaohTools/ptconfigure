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

        /*
         *
         *
         *
$priv_ssh_key_bastion = $priv_ssh_key_git = $priv_ssh_key_jenkins =
$priv_ssh_key_staging = $priv_ssh_key_production = $priv_ssh_key  ;

$provider_bastion = $provider_git = $provider_jenkins =
$provider_staging = $provider_production = $provider ;

$image_id_bastion = $image_id_git = $image_id_jenkins =
$image_id_staging = $image_id_production = $image_id ;

$region_id_bastion = $region_id_git = $region_id_jenkins =
$region_id_staging = $region_id_production = $region_id ;

$size_id_bastion = $size_id_git = $size_id_jenkins = $size_id_staging =
$size_id_production = $size_id ;
$size_id_jenkins = "62" ; // Jenkins is larger as behat was getting memory issues on install

$user_name_bastion = $user_name_git = $user_name_jenkins =
$user_name_staging = $user_name_production = $user_name ;

$box_amount_bastion = $box_amount_git = $box_amount_jenkins =
$box_amount_staging = $box_amount_production = $box_amount ;
         *
         */
        $this->steps =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a tiny set of environments"),),),

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
                    "box-user-name" => $box_user_name_git ,
                    "private-ssh-key-path" => $priv_ssh_key_git ,
                    "wait-for-box-info" => true,
                    "wait-until-active" => true,
                ),),),

                // Jenkins
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a Jenkins Box" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "tiny-jenkins",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "tiny-jenkins",
                    "provider-name" => $provider,
                    "box-amount" => $box_amount_jenkins ,
                    "image-id" => $image_id_jenkins ,
                    "region-id" => $region_id_jenkins ,
                    "size-id" => $size_id_jenkins ,
                    "server-prefix" => $prefix,
                    "box-user-name" => $box_user_name_jenkins ,
                    "private-ssh-key-path" => $priv_ssh_key_jenkins ,
                    "wait-for-box-info" => true,
                    "wait-until-active" => true,
                ),),),

                // Staging
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a Staging Box" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "tiny-staging",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "tiny-staging",
                    "provider-name" => $provider,
                    "box-amount" => $box_amount_staging ,
                    "image-id" => $image_id_staging ,
                    "region-id" => $region_id_staging ,
                    "size-id" => $size_id_staging ,
                    "server-prefix" => $prefix,
                    "box-user-name" => $box_user_name_staging ,
                    "private-ssh-key-path" => $priv_ssh_key_staging ,
                    "wait-for-box-info" => true,
                    "wait-until-active" => true,
                ),),),

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
                    "box-amount" => $box_amount_ ,
                    "image-id" => $image_id_ ,
                    "region-id" => $region_id_ ,
                    "size-id" => $size_id_ ,
                    "server-prefix" => $prefix,
                    "box-user-name" => $box_user_name_ ,
                    "private-ssh-key-path" => $priv_ssh_key_ ,
                    "wait-for-box-info" => true,
                    "wait-until-active" => true,
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a tiny set of environments complete"),),),

        );

    }

}
