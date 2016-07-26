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

                array ( "Logging" => array( "log" => array( "log-message" => "Lets configure PHP and Files for Pharaoh Build"),),),

                array ( "Mkdir" => array( "path" => array(
                    "label" => "Ensure the Pipes Directory exists",
                    "path" => PIPEDIR
                ), ), ),

                array ( "Chmod" => array( "path" => array(
                    "label" => "Ensure the Pipes Directory is writable",
                    "path" => PIPEDIR,
                    "recursive" => true,
                    "mode" => '0755',
                ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuration Management for Pharaoh Build Complete"),),),

            );

    }

}
