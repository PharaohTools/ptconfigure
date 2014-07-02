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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a Developer box on environment <%tpl.php%>env_name</%tpl.php%>"),),),

                // Standard Tools
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure some standard tools are installed" ),),),
                array ( "StandardTools" => array( "ensure" => array(),),),

                // Git Tools
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure some git tools are installed" ),),),
                array ( "GitTools" => array( "ensure" => array(),),),
                
                // Media Tools
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure some media tools are installed" ),),),
                array ( "MediaTools" => array( "ensure" => array(),),),

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
                    "background" => ""
                ) ) ),

                //Mysql
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Mysql Server is installed" ),),),
                array ( "MysqlServer" => array( "ensure" =>  array("version" => "5", "version-operator" => "+"), ), ),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure a Mysql Admin User is installed"),),),
                array ( "MysqlAdmins" => array( "install" => array (
                    "root-user" => "root",
                    "root-pass" => "cleopatra",
                    "new-user" => "dave",
                    "new-pass" => "golden",
                    "mysql-host" => "127.0.0.1"
                ) ) ),

                // Mysql Tools
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Mysql Tools are installed"),),),
                array ( "MysqlTools" => array( "ensure" => array("guess" => true ),),),

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
                ), ),),

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
                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a build server on environment <%tpl.php%>env_name</%tpl.php%> complete"),),),

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
                ), ),),

                // BDD Testing

                // SeleniumServer
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Selenium Server is installed"),),),
                array ( "SeleniumServer" => array( "ensure" => array("guess" => true ),),),

                // Behat
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Behat is installed"),),),
                array ( "Behat" => array( "ensure" => array("guess" => true ),),),

                // Ruby
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Ruby is installed"),),),
                array ( "RubySystem" => array( "ensure" => array("guess" => true ),),),

                // Ruby BDD Gems
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Ruby BDD Gems are installed"),),),
                array ( "RubyBDD" => array( "ensure" => array("guess" => true ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a Developer box on environment <%tpl.php%>env_name</%tpl.php%> complete"),),),

                // Unit Testing Tools

                // PHPUnit
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure PHPUnit is installed"),),),
                array ( "PHPUnit" => array( "ensure" => array("guess" => true ),),),

                // IDE's and Developer Tools

                // Developer Tools
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure DeveloperTools are installed"),),),
                array ( "DeveloperTools" => array( "ensure" => array("guess" => true ),),),

                // IntelliJ
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure IntelliJ IDE is installed"),),),
                array ( "IntelliJ" => array( "ensure" => array("guess" => true ),),),

        );

    }

}
