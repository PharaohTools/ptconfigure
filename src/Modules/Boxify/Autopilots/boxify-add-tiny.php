<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;

    public function __construct() {
        $this->setSteps();
    }

    /* Steps */
    private function setSteps() {

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
                    "provider-name" => "DigitalOcean",
                    "box-amount" => "1",
                    "image-id" => "3101045",
                    "region-id" => "2",
                    "size-id" => "66",
                    "server-prefix" => "tiny-test",
                    "box-user-name" => "root",
                    "private-ssh-key-path" => "/home/dave/.ssh/id_rsa",
                    "wait-for-box-info" => true,
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
                    "provider-name" => "DigitalOcean",
                    "box-amount" => "1",
                    "image-id" => "3101045",
                    "region-id" => "2",
                    "size-id" => "66",
                    "server-prefix" => "tiny-test",
                    "box-user-name" => "root",
                    "private-ssh-key-path" => "/home/dave/.ssh/id_rsa",
                    "wait-for-box-info" => true,
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
                    "provider-name" => "DigitalOcean",
                    "box-amount" => "1",
                    "image-id" => "3101045",
                    "region-id" => "2",
                    "size-id" => "66",
                    "server-prefix" => "tiny-test",
                    "box-user-name" => "root",
                    "private-ssh-key-path" => "/home/dave/.ssh/id_rsa",
                    "wait-for-box-info" => true,
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
                    "provider-name" => "DigitalOcean",
                    "box-amount" => "1",
                    "image-id" => "3101045",
                    "region-id" => "2",
                    "size-id" => "66",
                    "server-prefix" => "tiny-test",
                    "box-user-name" => "root",
                    "private-ssh-key-path" => "/home/dave/.ssh/id_rsa",
                    "wait-for-box-info" => true,
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
                    "provider-name" => "DigitalOcean",
                    "box-amount" => "1",
                    "image-id" => "3101045",
                    "region-id" => "2",
                    "size-id" => "66",
                    "server-prefix" => "tiny-test",
                    "box-user-name" => "root",
                    "private-ssh-key-path" => "/home/dave/.ssh/id_rsa",
                    "wait-for-box-info" => true,
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a tiny set of environments complete"),),),

        );

    }

}
