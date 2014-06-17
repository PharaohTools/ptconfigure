<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;
    protected $myUser ;

    public function __construct() {
        $this->setSteps();
    }

    /* Steps */
    private function setSteps() {

        $this->steps =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a Database Node on environment <%tpl.php%>env_name</%tpl.php%>"),),),

//                // Install Keys - Bastion Public Key
//                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure our Bastion Public Key is installed" ),),),
//                array ( "SshKeyInstall" => array( "file" =>
//                    array("public-key-file" => "build/config/cleopatra/SSH/keys/public/raw/bastion"),
//                    array("user-name" => "{$this->myUser}"),),),

                // SSH Hardening
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure we have some SSH Security" ),),),
                array ( "SshHarden" => array( "ensure" => array(),),),

                // Standard Tools
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure some standard tools are installed" ),),),
                array ( "StandardTools" => array( "ensure" => array(),),),

                // Git Tools
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure some git tools are installed" ),),),
                array ( "GitTools" => array( "ensure" => array(),),),

                // Git Key Safe
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Git SSH Key Safe version is are installed" ),),),
                array ( "GitKeySafe" => array( "ensure" => array(),),),

                // PHP Modules
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure our common PHP Modules are installed" ),),),
                array ( "PHPModules" => array( "ensure" => array(),),),

                // HA Proxy Cluster Addition step
                // @todo Install HA - should be done below
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure HA Proxy Server is installed" ),),),
                array ( "HAProxy" => array( "ensure" =>  array(), ), ),

                // HA Proxy Cluster configure step
                // @todo Configure HA - looks done?
                array ( "Logging" => array( "log" => array( "log-message" => "Lets HA Proxy to balance to our DB Nodes"),),),
                array ( "HAProxy" => array( "config" => array(
                    "environment-name" => "<%tpl.php%>db_nodes_env</%tpl.php%>",
                    "template_listen_mode" => "tcp",
                    "template_listen_ip_port" => "0.0.0.0:3306",
                    "template_target_port" => "3306",
                    "template_defaults_mode" => "tcp"
                ) ) ),

                // Firewall
                array ( "Logging" => array( "log" => array( "log-message" => "Lets disable Firewall to change settings"), ) , ) ,
                array ( "Firewall" => array( "disable" => array(), ) , ) ,
                array ( "Logging" => array( "log" => array( "log-message" => "Lets deny all input"), ) , ) ,
                array ( "Firewall" => array( "default" => array("policy" => "deny" ), ) , ) ,
                array ( "Logging" => array( "log" => array( "log-message" => "Lets allow SSH input"), ) , ) ,
                array ( "Firewall" => array( "allow" => array("firewall-rule" => "ssh/tcp" ), ) , ) ,
                array ( "Logging" => array( "log" => array( "log-message" => "Lets allow MySQL input"), ) , ) ,
                array ( "Firewall" => array( "allow" => array("firewall-rule" => "3306/tcp" ), ) , ) ,
                array ( "Logging" => array( "log" => array( "log-message" => "Lets enable Firewall again"), ) , ) ,
                array ( "Firewall" => array( "enable" => array(), ) , ) ,

                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a Database Node on environment <%tpl.php%>env_name</%tpl.php%> complete"),),),

        );

    }

}
