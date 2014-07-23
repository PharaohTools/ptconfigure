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

        /*
         *
         * Modules waiting
         * ------------------------
         *
         * Intended:
         * -- LAMP --
         * PHP Configuration for Developers (@todo)
         *
         * -- Other Languages --
         * Ruby --
         * Python --
         * Java JDK --
         * Node JS --
         *
         * -- Tools  --
         * Standard --
         * Network --
         * Media --
         *
         * -- Git --
         * Git Tools --
         * Git Key Safe --
         *
         * -- Package Managers --
         * Pear --
         *
         * -- Build Servers/CI Tools --
         * ThoughtWorks Go @todo Module ready but check it install doesnt break anything
         * Teamcity @todo Module ready but check it install doesnt break anything
         * Jenkins --
         * JenkinsSudo --
         * JenkinsPlugins --
         * Pharoes --
         * Drush --
         * Phing --
         * Phake --
         *
         * -- PHP Testing --
         * PHPMD --
         * PHPCS --
         *
         * -- JS, Security, Load, Break, PageSpeed, Systems Testing --
         * Javascript Testing  @todo
         * Security/Pen Testing  @todo
         * Load Testing  @todo
         * Break Testing  @todo
         * Page Speed Testing  @todo
         * Storyplayer/Systems Testing  @todo
         *
         *
         * -- BDD Testing --
         * Selenium --
         * Behat --
         * Ruby BDD Gems --
         *
         * -- IDE's and Developer Tools --
         * DeveloperTools --
         * IntelliJ --
         * PHPStorm --
         *
         * // // //
         *
         * -- Security Tools --
         * Network Security (Nagios)
         * Nagios todo
         *
         * -- Infrastructure Tools --
         * Foreman todo
         *
         * -- Containerisation --
         * Docker Manager todo
         *
         * -- Autopilots --
         * Dapper PostInput Autopilot todo
         * Jenkins RP Autopilot todo
         * Foreman RP Autopilot todo
         *
         */

        $this->steps =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a standalone server on environment <%tpl.php%>env_name</%tpl.php%>"),),),

                /* LAMP Start */

                // PHP Modules
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure our common PHP Modules are installed" ),),),
                array ( "PHPModules" => array( "ensure" => array(),),),

                // Apache
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Apache Server is installed" ),),),
                array ( "ApacheServer" => array( "ensure" =>  array("version" => "2.2"), ), ),

                // Apache Modules
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure our common Apache Modules are installed" ),),),
                array ( "ApacheModules" => array( "ensure" => array(),),),

                // Apache Modules
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure our Reverse Proxy Apache Modules are installed" ),),),
                array ( "ApacheReverseProxyModules" => array( "ensure" => array(),),),

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

                /* LAMP End */


                /* Other Languages */
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure other languages: Ruby, Python, Java and NodeJS  are installed"),),),
                array ( "RubySystem" => array( "ensure" => array("guess" => true ),),),
                array ( "Java" => array( "ensure" => array("guess" => true ),),),
                array ( "NodeJS" => array( "ensure" => array("guess" => true ),),),
                array ( "Python" => array( "ensure" => array("guess" => true ),),),

                /* Tools */
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure some Standard, Network and Media Tools are installed" ),),),
                array ( "StandardTools" => array( "ensure" => array(),),),
                array ( "NetworkTools" => array( "ensure" => array(),),),
                array ( "MediaTools" => array( "ensure" => array(),),),

                /* Git */
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure some Git Tools, and Git for SSH Keys are installed" ),),),
                array ( "GitTools" => array( "ensure" => array(),),),
                array ( "GitKeySafe" => array( "ensure" => array(),),),


                /* Package Managers */
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Pear is installed"),),),
                array ( "Pear" => array( "ensure" => array("guess" => true ),),),


                /* Build/CI Servers & Build Tools */

                // Jenkins
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Jenkins is installed" ),),),
                array ( "Jenkins" => array( "ensure" => array(),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Jenkins PHP Plugins are installed"),),),
                array ( "JenkinsPlugins" => array( "ensure" => array(),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure the Jenkins user can use Sudo without a Password"),),),
                array ( "JenkinsSudoNoPass" => array( "ensure" => array(),),),

                // All Pharoes
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure all Pharoah Tools exist" ),),),
                array ( "PharoahTools" => array( "ensure" => array(),),),

                // Drush
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Drush for Drupal" ),),),
                array ( "PackageManager" => array( "pkg-ensure" => array(
                    "package-name" => "drush/drush",
                    "packager-name" => "Pear",
                    "pear-channel" => "pear.drush.org",
                    "all-dependencies" => true
                ), ),),

                // Phing
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Phing is installed"),),),
                array ( "PackageManager" => array( "pkg-ensure" => array(
                    "package-name" => "phing/phing",
                    "packager-name" => "Pear",
                    "pear-channel" => "pear.phing.info",
                    "all-dependencies" => true
                ), ),),

                // Phake
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Phake is installed"),),),
                array ( "Phake" => array( "ensure" => array(),),),


                /* PHP Testing */
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure PHP Unit, PHP Mess Detector and PHP CodeSniffer are installed"),),),
                array ( "PHPMD" => array( "ensure" => array("guess" => true ),),),
                array ( "PHPCS" => array( "ensure" => array("guess" => true ),),),


                /* BDD Testing */

                // Selenium Server
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Selenium Server is installed"),),),
                array ( "SeleniumServer" => array( "ensure" => array("guess" => true ),),),

                // Behat
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Behat is installed"),),),
                array ( "Behat" => array( "ensure" => array("guess" => true ),),),

                // Ruby BDD Gems
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Ruby BDD Gems are installed"),),),
                array ( "RubyBDD" => array( "ensure" => array("guess" => true ),),),


                /* IDE's and Developer Tools */

                // Developer Tools
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Developer Tools are installed"),),),
                array ( "DeveloperTools" => array( "ensure" => array("guess" => true ),),),

                // IntelliJ
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure IntelliJ IDE is installed"),),),
                array ( "IntelliJ" => array( "ensure" => array("guess" => true ),),),

                // PHPStorm
                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure PHPStorm IDE is installed"),),),
                array ( "PHPStorm" => array( "ensure" => array("guess" => true ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a standalone server on environment <%tpl.php%>env_name</%tpl.php%> complete"),),),

        );

    }

}
