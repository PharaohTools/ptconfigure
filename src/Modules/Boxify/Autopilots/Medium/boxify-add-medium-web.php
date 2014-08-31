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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a medium set of environments"),),),

                // Bastion
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a Bastion Box" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "medium-bastion",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "medium-bastion",
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
                    "max-box-info-wait-time" => $wait_time,
                    "wait-until-active" => true,
                    "max-active-wait-time" => $wait_time,
                ),),),

                // Git
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a GitBucket Box" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "medium-git",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "medium-git",
                    "provider-name" => $provider,
                    "box-amount" => $box_amount_git ,
                    "image-id" => $image_id_git ,
                    "region-id" => $region_id_git ,
                    "size-id" => $size_id_git ,
                    "server-prefix" => $prefix,
                    "box-user-name" => $user_name_git ,
                    "ssh-key-name" => "$ssh_key_name",
                    "private-ssh-key-path" => $priv_ssh_key_git,
                    "wait-for-box-info" => true,
                    "max-box-info-wait-time" => $wait_time,
                    "wait-until-active" => true,
                    "max-active-wait-time" => $wait_time,
                ),),),

                // Jenkins
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a Jenkins Box" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "medium-jenkins",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "medium-jenkins",
                    "provider-name" => $provider,
                    "box-amount" => $box_amount_jenkins ,
                    "image-id" => $image_id_jenkins ,
                    "region-id" => $region_id_jenkins ,
                    "size-id" => $size_id_jenkins ,
                    "server-prefix" => $prefix,
                    "box-user-name" => $user_name_jenkins ,
                    "ssh-key-name" => "$ssh_key_name",
                    "private-ssh-key-path" => $priv_ssh_key_jenkins ,
                    "wait-for-box-info" => true,
                    "max-box-info-wait-time" => $wait_time,
                    "wait-until-active" => true,
                    "max-active-wait-time" => $wait_time,
                ),),),

                // Staging Web Nodes
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add Staging Web Nodes" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "medium-staging-web-nodes",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
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
                ),),),

                // Staging Load Balancer
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a Staging Load Balancer" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "medium-staging-load-balancer",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "medium-staging-load-balancer",
                    "provider-name" => "$provider_load_balancer",
                    "box-amount" => "$box_amount_load_balancer",
                    "image-id" => "$image_id_load_balancer",
                    "region-id" => "$region_id_load_balancer",
                    "size-id" => "$size_id_load_balancer",
                    "server-prefix" => $prefix,
                    "box-user-name" => "$user_name_load_balancer",
                    "ssh-key-name" => "$ssh_key_name",
                    "private-ssh-key-path" => "$priv_ssh_key_load_balancer",
                    "wait-for-box-info" => true,
                    "max-box-info-wait-time" => $wait_time,
                    "wait-until-active" => true,
                    "max-active-wait-time" => $wait_time,
                ),),),

                // Production Web Nodes
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add Production Web Nodes" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "medium-prod-web-nodes",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "medium-prod-web-nodes",
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
                ),),),

                // Production Load Balancer
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a Production Load Balancer" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "medium-prod-load-balancer",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "medium-prod-load-balancer",
                    "provider-name" => "$provider_load_balancer",
                    "box-amount" => "$box_amount_load_balancer",
                    "image-id" => "$image_id_load_balancer",
                    "region-id" => "$region_id_load_balancer",
                    "size-id" => "$size_id_load_balancer",
                    "server-prefix" => $prefix,
                    "box-user-name" => "$user_name_load_balancer",
                    "ssh-key-name" => "$ssh_key_name",
                    "private-ssh-key-path" => "$priv_ssh_key_load_balancer",
                    "wait-for-box-info" => true,
                    "max-box-info-wait-time" => $wait_time,
                    "wait-until-active" => true,
                    "max-active-wait-time" => $wait_time,
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a medium set of environments complete"),),),

            );

    }

}
