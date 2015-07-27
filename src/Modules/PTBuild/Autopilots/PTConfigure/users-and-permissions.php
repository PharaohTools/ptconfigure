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

                array ( "Logging" => array( "log" => array( "log-message" => "Lets configure users and permissions for Pharaoh Build"),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Allow user ptbuild a passwordless sudo", ), ), ),
                array ( "SudoNoPass" => array( "install" => array(
                    "guess" => true,
                    "install-user-name" => 'ptbuild',
                ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Make the PT Build Settings file writable", ), ), ),
                array ( "Chmod" => array( "path" => array(
                    "path" => PFILESDIR.'ptbuild'.DS.'ptbuild'.DS.'ptbuildvars',
                    "mode" => 0777,
                ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Ensure the Pipes Directory exists", ), ), ),
                array ( "Mkdir" => array( "path" => array(
                    "path" => PFILESDIR.'ptbuild'.DS.'pipes',
                    "mode" => 0777,
                ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Make the PT Build Settings file writable", ), ), ),
                array ( "Chmod" => array( "path" => array(
                    "path" => PFILESDIR.'ptbuild'.DS.'pipes',
                    "recursive" => true,
                    "mode" => 0777,
                ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuration Management for Pharaoh Build Complete"),),),

            );

    }

}
