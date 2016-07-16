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

                array ( "Logging" => array( "log" => array( "log-message" => "Lets configure PHP and Files for Pharaoh Web Application"),),),

                array ( "User" => array( "ensure-exists" => array(
                    "label" => "Ensure ptwebapplication user exists",
                    "username" => "ptwebapplication",
                    "fullname" => "ptwebapplication",
                    "home-directory" => "",
                    "shell" => "/bin/bash"
                ),),),

                array ( "PHPDefaults" => array( "install" => array(
                    "label" => "Ensure PHP Default Settings are okay",
                ), ), ),

                array ( "ApacheDefaults" => array( "install" => array(
                    "label" => "Ensure Apache Default Settings are okay",
                ), ), ),

                array ( "PHPFPM" => array( "ensure" => array(
                    "label" => "Ensure PHP FPM is installed",
                ), ), ),

                array ( "ApacheFastCGIModules" => array( "ensure" => array(
                    "label" => "Ensure Apache Fast CGI is installed",
                ), ), ),

                array ( "Chmod" => array( "path" => array(
                    "label" => "Make the PT Web Application Settings file writable",
                    "path" => PFILESDIR.'ptwebapplication'.DS.'ptwebapplication'.DS.'ptwebapplicationvars',
                    "mode" => '0755',
                ), ), ),

                array ( "Mkdir" => array( "path" => array(
                    "label" => "Ensure the Pipes Directory exists",
                    "path" => PIPEDIR
                ), ), ),

                array ( "Chmod" => array( "path" => array(
                    "label" => "Ensure the Pipes Directory is writable",
                    "path" => PIPEDIR,
                    "recursive" => true,
                    "mode" => '0755',
                ), ), ),

                array ( "Chown" => array( "path" => array(
                    "label" => "Ensure the Pharaoh Web Application user owns the Program Files",
                    "path" => PFILESDIR.'ptwebapplication'.DS,
                    "recursive" => true,
                    "user" => 'ptwebapplication',
                ), ), ),

                array ( "Chgrp" => array( "path" => array(
                    "label" => "Ensure the Pharaoh Group user owns the Program Files",
                    "path" => PFILESDIR.'ptwebapplication'.DS,
                    "recursive" => true,
                    "group" => 'ptwebapplication',
                ), ), ),

                array ( "Copy" => array( "put" => array(
                    "label" => "PTWebApplication PHP FPM Pool Config",
                    "source" => dirname(dirname(__DIR__)).DS.'Templates'.DS.'ptwebapplication_pool.conf',
                    "target" => "{{{ PTWebApplication::~::getApachePoolDir }}}/ptwebapplication.conf",
                ), ), ),

                array ( "PHPFPM" => array( "restart" => array(
                    "label" => "PTWebApplication PHP FPM Restart",
                ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuration Management for Pharaoh Web Application Complete"),),),

            );

    }

}
