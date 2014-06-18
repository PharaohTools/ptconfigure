<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;

    public function __construct() {
        $this->setSteps();
    }

    /* Steps */
    private function setSteps() {

        include ("settings.php") ;

        $this->steps =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Destroying of a medium set of environments"),),),

                // DB Nodes
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the DB Node Boxes" ),),),
                array ( "Boxify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "vsphere-cluster-db-nodes",
                    "provider-name" => "VSphere",
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "vsphere-cluster-db-nodes"
                ),),),

                // DB Load Balancer
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the DB Load Balancer Box" ),),),
                array ( "Boxify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "vsphere-cluster-db-balancer",
                    "provider-name" => "VSphere",
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "vsphere-cluster-db-balancer"
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Destroying a medium set of environments complete"),),),

            );

    }

}
