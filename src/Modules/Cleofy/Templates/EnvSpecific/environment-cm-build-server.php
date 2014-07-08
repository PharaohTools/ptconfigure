<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;

    public function __construct() {
        $this->setSteps();
    }

    /* Steps */
    private function setSteps() {

        // @todo find a better way to get this filename
        $reverseProxyAutopilot = "/opt/cleopatra/cleopatra/src/Modules/Jenkins/Autopilots/Dapperstrano/proxy-8080-to-80.php" ;

        $this->steps =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a Jenkins build server on environment <%tpl.php%>env_name</%tpl.php%>"),),),

//                // Install Keys - Bastion Public Key
//                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure our Bastion Public Key is installed" ),),),
//                array ( "Copy" => array( "file" =>
//                    array("from" => "build/config/cleopatra/SSH/keys/public/raw/bastion"),
//                    array("to" => "$HOME/.ssh/id_rsa"),
//                    // @todo Fix the key install!!
//                ),),

                // SSH Hardening
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure we have some SSH Security" ),),),
                array ( "SshHarden" => array( "ensure" => array(),),),

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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure our common Apache Modules are installed" ),),),
                array ( "ApacheModules" => array( "ensure" => array(),),),

                // Build Tools

                // Pear
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Pear is installed"),),),
                array ( "Pear" => array( "ensure" => array("guess" => true ),),),

                // Phing
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Phing is installed"),),),
                array ( "PackageManager" => array( "pkg-ensure" => array(
                    "package-name" => "phing/phing",
                    "packager-name" => "Pear",
                    "pear-channel" => "pear.phing.info",
                    "all-dependencies" => true
                ),),),

                // Drush
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Drush for Drupal" ),),),
                array ( "PackageManager" => array( "pkg-ensure" => array(
                    "package-name" => "phing/phing",
                    "packager-name" => "Pear",
                    "pear-channel" => "pear.drush.org",
                    "all-dependencies" => true
                ),),),

                // Java
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Java is installed"),),),
                array ( "Java" => array( "ensure" => array("guess" => true ),),),

                // Jenkins
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Jenkins is installed" ),),),
                array ( "Jenkins" => array( "install" => array(),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Jenkins PHP Plugins are installed"),),),
                array ( "JenkinsPlugins" => array( "install" => array(),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure the Jenkins user can use Sudo without a Password"),),),
                array ( "JenkinsSudoNoPass" => array( "install" => array(),),),

                // All Pharoes
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Cleopatra" ),),),
                array ( "Cleopatra" => array( "ensure" => array(),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Dapperstrano" ),),),
                array ( "Dapperstrano" => array( "ensure" => array(),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Testingkamen" ),),),
                array ( "Testingkamen" => array( "ensure" => array(),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure JRush for Joomla" ),),),
                array ( "JRush" => array( "ensure" => array(),),),

                // BDD Testing

                // SeleniumServer
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Selenium Server is installed"),),),
                array ( "SeleniumServer" => array( "ensure" => array("guess" => true ),),),

                // Start the Selenium Server
                array ( "Logging" => array( "log" => array( "log-message" => "Lets also start Selenium so we can use it"),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => 'printf "\n" | java -jar /opt/selenium/selenium-server.jar &',
                    "nohup" => true
                ),),),

                // Behat
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Behat is installed"),),),
                array ( "Behat" => array( "ensure" => array("guess" => true ),),),

                // Ruby
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Ruby is installed"),),),
                array ( "RubySystem" => array( "ensure" => array("guess" => true ),),),

                // Ruby BDD Gems
                // @todo this should be hidden as the install is failing, troublehoot and re -enable
//                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Ruby BDD Gems are installed"),),),
//                array ( "RubyBDD" => array( "ensure" => array("guess" => true ),),),


                // Unit Testing Tools

                // PHPUnit
                // @todo this is almost definitely not the right way to install PHPunit,
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure PHPUnit is installed"),),),
                array ( "PHPUnit" => array( "ensure" => array("guess" => true ),),),

                // Reverse proxy port 8080 to port 80, so we can close 80800 in the firewall
                array ( "Logging" => array( "log" => array( "log-message" => "Lets dapper a reverse proxy"),),),
                array ( "RunCommand" => array("install" => array(
                    "guess" => true,
                    "command" => "dapperstrano autopilot execute --autopilot-file=$reverseProxyAutopilot --vhe-url=<%tpl.php%>first_server_target</%tpl.php%>",
                ),),),

                // Firewall
                array ( "Logging" => array( "log" => array( "log-message" => "Lets disable Firewall to change settings"), ) , ) ,
                array ( "Firewall" => array( "disable" => array(), ) , ) ,
                array ( "Logging" => array( "log" => array( "log-message" => "Lets deny all input"), ) , ) ,
                array ( "Firewall" => array( "default" => array("policy" => "deny" ), ) , ) ,
                array ( "Logging" => array( "log" => array( "log-message" => "Lets allow SSH input"), ) , ) ,
                array ( "Firewall" => array( "allow" => array("firewall-rule" => "ssh/tcp" ), ) , ) ,
                array ( "Logging" => array( "log" => array( "log-message" => "Lets allow HTTP input"), ) , ) ,
                array ( "Firewall" => array( "allow" => array("firewall-rule" => "http/tcp" ), ) , ) ,
                array ( "Logging" => array( "log" => array( "log-message" => "Lets allow HTTPS input"), ) , ) ,
                array ( "Firewall" => array( "allow" => array("firewall-rule" => "https/tcp" ), ) , ) ,
                array ( "Logging" => array( "log" => array( "log-message" => "Lets enable Firewall again"), ) , ) ,
                array ( "Firewall" => array( "enable" => array(), ) , ) ,

                // End
                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a build server on environment <%tpl.php%>env_name</%tpl.php%> complete"),),),

            );

    }

}
