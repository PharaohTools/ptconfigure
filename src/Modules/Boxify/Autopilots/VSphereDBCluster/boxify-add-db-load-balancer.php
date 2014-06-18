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
                // DB Load Balancer
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add the DB Load Balancer Environment" ),),),
                array ( "EnvironmentConfig" => array("configure" => array(
                    "guess" => true,
                    "environment-name" => "vsphere-cluster-db-balancer",
                    "tmp-dir" => "/tmp/",
                    "keep-current-environments" => true,
                    "no-manual-servers" => true,
                    "add-single-environment" => true,
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add the DB Load Balancer Box" ),),),
                // @todo set up boxify to accept box-clone at some point
                array ( "VSphere" => array("box-clone" => array(
                    "guess" => true,
                    "environment-name" => "vsphere-cluster-db-balancer",
                    "provider-name" => "$provider_db_balancer",
                    "box-amount" => "$box_amount_db_balancer",
                    "server-prefix" => $prefix,
                    "server-suffix" => $suffix,
                    "box-user-name" => "$user_name_db_balancer",
                    "private-ssh-key-path" => "$priv_ssh_key_db_balancer",
                    "wait-until-active" => true,
                    "max-active-wait-time" => $wait_time,
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Creating vsphere-cluster-db-balancer environment complete"),),),

            );

    }

}
