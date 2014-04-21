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
                array ( "Logging" => array( "log" =>
                    array( "log-message" => "Lets begin Configuration of a build server on environment <%tpl.php%>env_name</%tpl.php%>"),
                ) , ) ,
//                array ( "Logging" => array( "log" =>
//                    array( "log-message" => "Lets block all input"),
//                ) , ) ,
//                array ( "Firewall" => array( "deny" =>
//                    array("firewall-rule" => "ssh/tcp" ),
//                ) , ) ,
//                array ( "Logging" => array( "log" =>
//                    array( "log-message" => "Lets block all output"),
//                ) , ) ,
//                array ( "Firewall" => array( "allow" =>
//                    array("firewall-rule" => "ssh/https" ),
//                ) , ) ,
//                array ( "Logging" => array( "log" =>
//                    array( "log-message" => "Lets allow SSH input"),
//                ) , ) ,
//                array ( "Firewall" => array( "allow" =>
//                    array("firewall-rule" => "ssh/tcp" ),
//                ) , ) ,
//                array ( "Logging" => array( "log" =>
//                    array( "log-message" => "Lets allow HTTPS input"),
//                ) , ) ,
//                array ( "Firewall" => array( "allow" =>
//                    array("firewall-rule" => "ssh/https" ),
//                ) , ) ,
//                array ( "Logging" => array( "log" =>
//                    array( "log-message" => "Lets allow HTTP input"),
//                ) , ) ,
//                array ( "Firewall" => array( "allow" =>
//                    array("firewall-rule" => "ssh/http" ),
//                ) , ) ,
                array ( "Logging" => array( "log" =>
                    array( "log-message" => "Lets install some standard tools"),
                ) , ) ,
                array ( "StandardTools" => array( "install" =>
                    array(),
                ) , ) ,
                array ( "Logging" => array( "log" =>
                    array( "log-message" => "Lets install some Git Tools"),
                ) , ) ,
                array ( "Java" => array( "ensure" =>
                    array("guess" => true ),
                ) , ) ,
                array ( "Logging" => array( "log" =>
                    array( "log-message" => "Lets install some Git Tools"),
                ) , ) ,
                array ( "GitTools" => array( "install" =>
                    array(),
                ) , ) ,
                array ( "Logging" => array( "log" =>
                    array( "log-message" => "Lets install some Git Tools"),
                ) , ) ,
                array ( "GitTools" => array( "install" =>
                    array(),
                ) , ) ,
                array ( "Logging" => array( "log" =>
                    array( "log-message" => "Lets install some Git Tools"),
                ) , ) ,
                array ( "GitTools" => array( "install" =>
                    array(),
                ) , ) ,
                array ( "Logging" => array( "log" =>
                    array( "log-message" => "Configuring a build server on environment <%tpl.php%>env_name</%tpl.php%> complete"),
                ) , ) ,
        );

    }

}
