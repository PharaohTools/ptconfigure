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
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "bastion",
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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a Bastion Box" ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "jenkins",
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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a Bastion Box" ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "staging",
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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add a Bastion Box" ),),),
                array ( "Boxify" => array("box-add" => array(
                    "guess" => true,
                    "environment-name" => "production",
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
