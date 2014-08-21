<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;
    protected $myUser ;

    public function __construct() {
        $this->setSteps();
    }

    /* Steps */
    protected function setSteps() {

        $this->steps =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a Phlagrant Host"),),),

                // Copy SSH Private Key
                array ( "Logging" => array( "log" => array( "log-message" => "Lets push over our user SSH Keys" ),),),
                array ( "SFTP" => array( "put" => array(
                    "guess" => true,
                    "source" => "/home/{$this->myUser}/.ssh/id_rsa",
                    "target" => "/home/phlagrant/.ssh/id_rsa"
                ),),),

                array ( "Logging" => array( "log" => array(
                    "log-message" => "Cleopatra Configuration Management of your Phlagrant Host complete"
                ),),),

            );

    }

}
