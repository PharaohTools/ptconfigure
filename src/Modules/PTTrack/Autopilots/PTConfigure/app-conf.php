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

        $this->steps =
            array(

                array ( "Logging" => array( "log" => array( "log-message" => "Lets configure users and permissions for Pharaoh Track"),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Allow user pttrack a passwordless sudo", ), ), ),
                array ( "SudoNoPass" => array( "install" => array(
                    "guess" => true,
                    "install-user-name" => 'pttrack',
                ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Make the PT Track Settings file writable", ), ), ),
                array ( "Chmod" => array( "path" => array(
                    "path" => PFILESDIR.'pttrack'.DS.'pttrack'.DS.'pttrackvars',
                    "mode" => "777",
                ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Ensure the Jobs Directory exists", ), ), ),
                array ( "Mkdir" => array( "path" => array(
                    "path" => PFILESDIR.'pttrack'.DS.'data',
                    "mode" => "755",
                ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuration Management for Pharaoh Track Complete"),),),

            );

    }

}
