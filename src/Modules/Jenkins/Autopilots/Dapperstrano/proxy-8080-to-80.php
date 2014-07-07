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

            array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a Jenkins build server on environment tiny-jenkins"),),),

            // Install Apache Reverse Proxy
            array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Dapperstrano so we c" ),),),
            array ( "ApacheVHostEditor" => array( "add-balancer" => array(
                "guess" => true,
                "vhe-url" => "www.jenkins.tld",
                "vhe-ip-port" => "127.0.0.1:80",
                "vhe-cluster-name" => "jenkins-proxy",
                // @todo we should let it guess this, and make sure the ubuntu 14 mode provide s correct result
                // ubuntu 14 dapper model should guess .conf whether its centos or ubuntu, past ubuntu 2.4
                "vhe-file-ext" => ".conf",
                "vhe-default-template-name" => "http",
                "environment-name" => "local",
                "with-http-port-proxy" => true
            ),),),

            array ( "Logging" => array( "log" => array( "log-message" => "Now lets restart Apache so we are serving our new application version", ), ), ),
            array ( "ApacheControl" => array( "restart" => array(
                "guess" => true,
            ), ), ),

            // End
            array ( "Logging" => array( "log" => array( "log-message" => "Configuring a build server on environment tiny-jenkins complete"),),),

        );

    }

}
