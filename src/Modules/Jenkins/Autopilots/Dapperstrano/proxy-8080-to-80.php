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

            array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a Jenkins build server on environment tiny-jenkins"),),),

            // All Pharoes
            array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Dapperstrano so we c" ),),),
            array ( "Dapperstrano" => array( "ensure" => array(),),),

            // BDD Testing

            // SeleniumServer
            array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure Selenium Server is installed"),),),
            array ( "SeleniumServer" => array( "ensure" => array("guess" => true ),),),

            // @todo Runcommand is, im pretty sure, not working properly for starting this background process
            // Start the Selenium Server
            array ( "Logging" => array( "log" => array( "log-message" => "Lets also start Selenium so we can use it"),),),
            array ( "RunCommand" => array("install" => array(
                "command" => 'printf "\n" | java -jar /opt/selenium/selenium-server.jar &',
                "nohup" => true
            ),),),

            // End
            array ( "Logging" => array( "log" => array( "log-message" => "Configuring a build server on environment tiny-jenkins complete"),),),

        );

    }

}
