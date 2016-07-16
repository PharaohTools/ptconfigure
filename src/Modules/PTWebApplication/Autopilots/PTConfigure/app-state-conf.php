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

                array ( "Logging" => array( "log" => array(
                    "log-message" => "Lets configure PHP and Files for Pharaoh Web Application"
                ),),),

                array ( "User" => array( "ensure-exists" => array(
                    "label" => "Ensure {{{ Parameter::app-slug }}} user exists",
                    "username" => "{{{ Parameter::app-slug }}}",
                    "fullname" => "{{{ Parameter::app-slug }}}",
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
                    "path" => PFILESDIR.'{{{ Parameter::app-slug }}}'.DS.'{{{ Parameter::app-slug }}}'.DS.'{{{ Parameter::app-slug }}}vars',
                    "mode" => '755',
                ), ), ),

                array ( "Chown" => array( "path" => array(
                    "label" => "Ensure the Pharaoh Web Application user owns the Program Files",
                    "path" => PFILESDIR.'{{{ Parameter::app-slug }}}'.DS,
                    "recursive" => true,
                    "user" => '{{{ Parameter::app-slug }}}',
                ), ), ),

                array ( "Chgrp" => array( "path" => array(
                    "label" => "Ensure the Pharaoh Group user owns the Program Files",
                    "path" => PFILESDIR.'{{{ Parameter::app-slug }}}'.DS,
                    "recursive" => true,
                    "group" => '{{{ Parameter::app-slug }}}',
                ), ), ),

                array ( "Templating" => array( "install" => array(
                    "label" => "{{{ Parameter::app-slug }}} PHP FPM Pool Config",
                    "source" => dirname(dirname(__DIR__)).DS.'Templates'.DS.'ptapplication_pool.tpl.php',
                    "target" => "{{{ PTWebApplication::~::getApachePoolDir }}}/{{{ Parameter::app-slug }}}.conf",
                    "template_app-slug" => "{{{ Parameter::app-slug }}}",
                    "template_fpm-port" => "{{{ Parameter::fpm-port }}}",
                ), ), ),

                array ( "PHPFPM" => array( "restart" => array(
                    "label" => "{{{ Parameter::app-slug }}} PHP FPM Restart",
                ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuration Management for Pharaoh Web Application Complete"),),),

            );

    }

}
