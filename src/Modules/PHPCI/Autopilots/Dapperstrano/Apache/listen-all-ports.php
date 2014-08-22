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

        $template_vhost_path = dirname(dirname(dirname(dirname(__FILE__))))."/Templates/ApacheVHost/all-ports-14.04.conf" ;
        $template_vhost = file_get_contents($template_vhost_path);

        $this->steps =
            array(

                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a Web Server for PHPCI"),),),

                // Install Host Entry
                array ( "HostEditor" => array( "add" => array(
                    "host-ip" => "127.0.0.1",
                    "host-name" => "www.phpci.local",
                ),),),

                // Install Apache Reverse Proxy
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Add our Apache VHost" ),),),
                array ( "ApacheVHostEditor" => array( "add" => array(
                    "guess" => true,
                    "vhe-docroot" => "/opt/phpci/phpci/public/",
                    "vhe-url" => "www.phpci.local",
                    "vhe-ip-port" => "127.0.0.1:80",
                    "vhe-template" => $template_vhost,
                    "environment-name" => "local"
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Now lets restart Apache so we are serving our new application", ), ), ),
                array ( "ApacheControl" => array( "restart" => array(
                    "guess" => true,
                ), ), ),

                // End
                array ( "Logging" => array( "log" => array( "log-message" => "Configuration of a Web Server for PHPCI complete"),),),

            );

    }

}
