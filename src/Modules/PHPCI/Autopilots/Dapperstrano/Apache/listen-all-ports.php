<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;

    public function __construct($params = null) {
        parent::__construct($params);
        $this->setSteps();
    }

    /* Steps */
    private function setSteps() {

        $this->steps =
            array(

                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a Reverse Proxy from 8080 to 80"),),),

                // Install Apache Reverse Proxy
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Add our reverse proxy Apache VHost" ),),),
                array ( "ApacheVHostEditor" => array( "add-balancer" => array(
                    "guess" => true,
                    // "vhe-url" => "", this variable is pumped in from parent
                    // "vhe-ip-port" => "", this variable is pumped in from parent
                    "vhe-cluster-name" => "phpci-proxy",
                    // @todo we should let it guess this, and make sure the ubuntu 14 mode provide s correct result
                    // ubuntu 14 dapper model should guess .conf whether its centos or ubuntu, past ubuntu 2.4
                    "vhe-file-ext" => ".conf",
                    "vhe-default-template-name" => "http",
                    "environment-name" => "local"
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Now lets restart Apache so we are serving our new proxy", ), ), ),
                array ( "ApacheControl" => array( "restart" => array(
                    "guess" => true,
                ), ), ),

                // End
                array ( "Logging" => array( "log" => array( "log-message" => "Configuration of a Reverse Proxy from 8080 to 80 complete"),),),

            );

    }

}
