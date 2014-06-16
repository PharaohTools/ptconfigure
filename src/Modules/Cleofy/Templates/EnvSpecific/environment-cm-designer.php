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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a standalone server on environment <%tpl.php%>env_name</%tpl.php%>"),),),

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

                // Apache
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Apache Server is installed" ),),),
                array ( "ApacheServer" => array( "ensure" =>  array("version" => "2.2"), ), ),

                // Apache Modules
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure our common Apache Modules are installed" ),),),
                array ( "ApacheModules" => array( "ensure" => array(),),),

                // Restart Apache for new modules
                array ( "Logging" => array( "log" => array( "log-message" => "Lets restart Apache for our PHP and Apache Modules" ),),),
                array ( "RunCommand" => array( "restart" => array(
                    "guess" => true,
                    "command" => "dapperstrano ApacheCtl restart --yes",
                ) ) ),

                //Mysql
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Mysql Server is installed" ),),),
                array ( "MysqlServer" => array( "ensure" =>  array("version" => "5", "version-operator" => "+"), ), ),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure a Mysql Admin User is installed"),),),
                array ( "MysqlAdmins" => array( "install" => array(
                    "root-user" => "root",
                    "root-pass" => "cleopatra",
                    "new-user" => "root",
                    "new-pass" => "root",
                    "mysql-host" => "127.0.0.1"
                ) ) ),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a standalone server on environment <%tpl.php%>env_name</%tpl.php%> complete"),),),

                /*
//                array ( "Logging" => array( "log" => array( "log-message" => "Lets block all input"), ) , ) ,
//                array ( "Firewall" => array( "deny" => array("firewall-rule" => "ssh/tcp" ), ) , ) ,
//                array ( "Logging" => array( "log" => array( "log-message" => "Lets block all output"), ) , ) ,
//                array ( "Firewall" => array( "allow" => array("firewall-rule" => "ssh/https" ), ) , ) ,
//                array ( "Logging" => array( "log" => array( "log-message" => "Lets allow SSH input"), ) , ) ,
//                array ( "Firewall" => array( "allow" => array("firewall-rule" => "ssh/tcp" ), ) , ) ,
//                array ( "Logging" => array( "log" => array( "log-message" => "Lets allow HTTPS input"), ) , ) ,
//                array ( "Firewall" => array( "allow" => array("firewall-rule" => "ssh/https" ), ) , ) ,
//                array ( "Logging" => array( "log" => array( "log-message" => "Lets allow HTTP input"), ) , ) ,
//                array ( "Firewall" => array( "allow" => array("firewall-rule" => "ssh/http" ), ) , ) ,
                */


        );

    }

}
