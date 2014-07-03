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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a Bastion server on environment <%tpl.php%>env_name</%tpl.php%>"),),),

//                // Install Keys - Bastion Public Key, DevOps Public Key, Bastion Private Key
//                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure our Bastion Public Key is installed" ),),),
//                array ( "SshKeyInstall" => array( "file" => array(
//                    "public-key-file" => "build/config/cleopatra/SSH/keys/public/raw/bastion",
//                    "user-name" => "{$this->myUser}"
//                ), ), ),
//                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure our DevOps Public Key is installed" ),),),
//                array ( "SshKeyInstall" => array( "file" => array(
//                    "public-key-file" => "build/config/cleopatra/SSH/keys/public/raw/bastion",
//                    "user-name" => "{$this->myUser}"
//                ), ), ),
//                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure our Bastion Private Key is installed" ),),),
//                // @todo if this is run over ssh from another machine (DevOps laptop), the encryption key never needs to be on the target
//                // box might not even need encryption... look at this
//                array ( "Encryption" => array( "uninstall" => array(
//                    "encrypted-data" => "build/config/cleopatra/SSH/keys/private/encrypted/bastion",
//                    "encryption-target-file" => "{$this->myUserHome}/.ssh/bastion",
//                    // @todo the key thing
//                    "encryption-key" => "{$this->myUser}",
//                    "encryption-file-permissions" => "",
//                    "encryption-file-owner" => "",
//                    "encryption-group" => ""
//                ), ), ),

                // SSH Hardening
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure we have some SSH Security" ),),),
                array ( "SshHarden" => array( "ensure" => array( ),),),

                // Standard Tools
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure some standard tools are installed" ),),),
                array ( "StandardTools" => array( "ensure" => array(),),),

                // Git Tools
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure some git tools are installed" ),),),
                array ( "GitTools" => array( "ensure" => array(),),),

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
                ), ), ),

                // All Pharoes
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Cleopatra" ),),),
                array ( "Cleopatra" => array( "ensure" => array(),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Dapperstrano" ),),),
                array ( "Dapperstrano" => array( "ensure" => array(),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Testingkamen" ),),),
                array ( "Testingkamen" => array( "ensure" => array(),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure JRush for Joomla" ),),),
                array ( "JRush" => array( "ensure" => array(),),),

                // Drush
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Drush for Drupal" ),),),
                array ( "PackageManager" => array( "pkg-ensure" => array(
                    "package-name" => "drush/drush",
                    "packager-name" => "Pear",
                    "pear-channel" => "pear.drush.org",
                    "all-dependencies" => true
                ), ), ),

                // Firewall
                array ( "Logging" => array( "log" => array( "log-message" => "Lets disable Firewall to change settings"), ) , ) ,
                array ( "Firewall" => array( "disable" => array(), ) , ) ,
                array ( "Logging" => array( "log" => array( "log-message" => "Lets deny all input"), ) , ) ,
                array ( "Firewall" => array( "default" => array("policy" => "deny" ), ) , ) ,
                array ( "Logging" => array( "log" => array( "log-message" => "Lets allow SSH input"), ) , ) ,
                array ( "Firewall" => array( "allow" => array("firewall-rule" => "ssh/tcp" ), ) , ) ,
                array ( "Logging" => array( "log" => array( "log-message" => "Lets enable Firewall again"), ) , ) ,
                array ( "Firewall" => array( "enable" => array(), ) , ) ,

                array ( "Logging" => array( "log" => array(
                    "log-message" => "Configuring a Bastion server on environment <%tpl.php%>env_name</%tpl.php%> complete"
                ),),),

        );

    }

}
