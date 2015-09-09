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
        $apache_user = $this->getApacheUser();
        $this->steps =
            array(

                array ( "Logging" => array( "log" => array( "log-message" => "Lets configure users and permissions for Pharaoh Build"),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Allow user ptbuild a passwordless sudo", ), ), ),
                array ( "SudoNoPass" => array( "install" => array(
                    "guess" => true,
                    "install-user-name" => 'ptbuild',
                ), ), ),
//
//                array ( "Logging" => array( "log" => array( "log-message" => "Allow web server user $apache_user a passwordless sudo", ), ), ),
//                array ( "SudoNoPass" => array( "install" => array(
//                    "guess" => true,
//                    "install-user-name" => $apache_user,
//                ), ), ),
//
//                array ( "Logging" => array( "log" => array( "log-message" => "Allow apache user to switch to ptbuild user", ), ), ),
//                array ( "File" => array( "should-have-line" => array(
//                    "guess" => true,
//                    "file" => "/etc/sudoers",
//                    "search" => "{$apache_user}    ALL=(ptbuild) NOPASSWD: ALL",
//                ), ), ),

            array ( "Logging" => array( "log" => array( "log-message" => "Ensure PHP FPM is installed", ), ), ),
            array ( "PHPFPM" => array( "ensure" => array( ), ), ),

            array ( "Logging" => array( "log" => array( "log-message" => "Ensure Apache Fast CGI is installed", ), ), ),
            array ( "ApacheFastCGIModules" => array( "ensure" => array( ), ), ),

            array ( "Logging" => array( "log" => array( "log-message" => "Make the PT Build Settings file writable", ), ), ),
            array ( "Chmod" => array( "path" => array(
                "path" => PFILESDIR.'ptbuild'.DS.'ptbuild'.DS.'ptbuildvars',
                "mode" => '0777',
            ), ), ),

            array ( "Logging" => array( "log" => array( "log-message" => "Ensure the Pipes Directory exists", ), ), ),
            array ( "Mkdir" => array( "path" => array(
                "path" => PIPEDIR
            ), ), ),

            array ( "Logging" => array( "log" => array( "log-message" => "Ensure the Pipes Directory is writable", ), ), ),
            array ( "Chmod" => array( "path" => array(
                "path" => PIPEDIR,
                "recursive" => true,
                "mode" => '0777',
            ), ), ),

            array ( "Logging" => array( "log" => array( "log-message" => "PTBuild PHP FPM Pool Config", ), ), ),
            array ( "Copy" => array( "put" => array(
                "source" => dirname(dirname(__DIR__)).DS.'Templates'.DS.'ptbuild_pool.conf',
                "target" => '/etc/php5/fpm/pool.d/ptbuild.conf',
            ), ), ),

            array ( "Logging" => array( "log" => array( "log-message" => "PTBuild PHP FPM Restart", ), ), ),
            array ( "Service" => array( "restart" => array(
                "name" => 'php5-fpm',
            ), ), ),

            array ( "Logging" => array( "log" => array( "log-message" => "Apache Restart", ), ), ),
            array ( "Service" => array( "restart" => array(
                "name" => 'php5-fpm',
            ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuration Management for Pharaoh Build Complete"),),),

            );

    }

    protected function getApacheUser() {
        $system = new \Model\SystemDetection();
        $thisSystem = $system->getModel($this->params);
        if (in_array($thisSystem->os, array("Darwin") ) ) {
            $apacheUser = "_www" ; }
        else if ($thisSystem->os == "Linux" && in_array($thisSystem->os, array("Debian") ) ) {
            $apacheUser = "www-data" ; }
        else if ($thisSystem->os == "Linux" && in_array($thisSystem->os, array("Redhat") ) ) {
            $apacheUser = "httpd" ; }
        else {
            $apacheUser = "www-data" ; }
        return $apacheUser ;
    }

    protected function getApacheHttpdconfLocation() {
        $system = new \Model\SystemDetection();
        $thisSystem = $system->getModel($this->params);
        if (in_array($thisSystem->os, array("Darwin") ) ) {
            $apacheUser = "_www" ; }
        else if ($thisSystem->os == "Linux" && in_array($thisSystem->os, array("Debian") ) ) {
            $apacheUser = "www-data" ; }
        else if ($thisSystem->os == "Linux" && in_array($thisSystem->os, array("Redhat") ) ) {
            $apacheUser = "httpd" ; }
        else {
            $apacheUser = "www-data" ; }
        return $apacheUser ;
    }

}
