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
                    "provider-name" => "DigitalOcean",
                    "box-amount" => "1",
                    "image-id" => "3101045",
                    "region-id" => "2",
                    "size-id" => "66",
                    "server-prefix" => $prefix,
                    "box-user-name" => "root",
                    "private-ssh-key-path" => "/home/dave/.ssh/id_rsa",
                    "wait-for-box-info" => true,
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
                    "provider-name" => "DigitalOcean",
                    "box-amount" => "1",
                    "image-id" => "3101045",
                    "region-id" => "2",
                    "size-id" => "66",
                    "server-prefix" => $prefix,
                    "box-user-name" => "root",
                    "private-ssh-key-path" => "/home/dave/.ssh/id_rsa",
                    "wait-for-box-info" => true,
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
                    "provider-name" => "DigitalOcean",
                    "box-amount" => "1",
                    "image-id" => "3101045",
                    "region-id" => "2",
                    "size-id" => "66",
                    "server-prefix" => $prefix,
                    "box-user-name" => "root",
                    "private-ssh-key-path" => "/home/dave/.ssh/id_rsa",
                    "wait-for-box-info" => true,
                ),),),

                // Staging Primary DB
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a Staging Primary DB" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "medium-staging-primary-db",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "medium-staging-primary-db",
                    "provider-name" => "DigitalOcean",
                    "box-amount" => "1",
                    "image-id" => "3101045",
                    "region-id" => "2",
                    "size-id" => "66",
                    "server-prefix" => $prefix,
                    "box-user-name" => "root",
                    "private-ssh-key-path" => "/home/dave/.ssh/id_rsa",
                    "wait-for-box-info" => true,
                ),),),

                // Staging DB Nodes
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add Staging DB Nodes" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "medium-staging-secondary-db",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "medium-staging-secondary-db",
                    "provider-name" => "DigitalOcean",
                    "box-amount" => "2",
                    "image-id" => "3101045",
                    "region-id" => "2",
                    "size-id" => "66",
                    "server-prefix" => $prefix,
                    "box-user-name" => "root",
                    "private-ssh-key-path" => "/home/dave/.ssh/id_rsa",
                    "wait-for-box-info" => true,
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
                    "provider-name" => "DigitalOcean",
                    "box-amount" => "2",
                    "image-id" => "3101045",
                    "region-id" => "2",
                    "size-id" => "66",
                    "server-prefix" => $prefix,
                    "box-user-name" => "root",
                    "private-ssh-key-path" => "/home/dave/.ssh/id_rsa",
                    "wait-for-box-info" => true,
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
                    "provider-name" => "DigitalOcean",
                    "box-amount" => "1",
                    "image-id" => "3101045",
                    "region-id" => "2",
                    "size-id" => "66",
                    "server-prefix" => $prefix,
                    "box-user-name" => "root",
                    "private-ssh-key-path" => "/home/dave/.ssh/id_rsa",
                    "wait-for-box-info" => true,
                ),),),

                // Production Primary DB
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a Production Primary DB" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "medium-prod-primary-db",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "medium-prod-primary-db",
                    "provider-name" => "DigitalOcean",
                    "box-amount" => "1",
                    "image-id" => "3101045",
                    "region-id" => "2",
                    "size-id" => "66",
                    "server-prefix" => $prefix,
                    "box-user-name" => "root",
                    "private-ssh-key-path" => "/home/dave/.ssh/id_rsa",
                    "wait-for-box-info" => true,
                ),),),

                // Production DB Nodes
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add Production DB Nodes" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "medium-prod-secondary-db",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "medium-prod-secondary-db",
                    "provider-name" => "DigitalOcean",
                    "box-amount" => "2",
                    "image-id" => "3101045",
                    "region-id" => "2",
                    "size-id" => "66",
                    "server-prefix" => $prefix,
                    "box-user-name" => "root",
                    "private-ssh-key-path" => "/home/dave/.ssh/id_rsa",
                    "wait-for-box-info" => true,
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
                    "provider-name" => "DigitalOcean",
                    "box-amount" => "2",
                    "image-id" => "3101045",
                    "region-id" => "2",
                    "size-id" => "66",
                    "server-prefix" => $prefix,
                    "box-user-name" => "root",
                    "private-ssh-key-path" => "/home/dave/.ssh/id_rsa",
                    "wait-for-box-info" => true,
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
                    "provider-name" => "DigitalOcean",
                    "box-amount" => "1",
                    "image-id" => "3101045",
                    "region-id" => "2",
                    "size-id" => "66",
                    "server-prefix" => $prefix,
                    "box-user-name" => "root",
                    "private-ssh-key-path" => "/home/dave/.ssh/id_rsa",
                    "wait-for-box-info" => true,
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a medium set of environments complete"),),),

            );

    }

}
