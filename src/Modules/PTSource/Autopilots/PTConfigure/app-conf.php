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

                array ( "Logging" => array( "log" => array( "log-message" => "Lets configure PHP and Files for Pharaoh Source"),),),

                array ( "Mkdir" => array( "path" => array(
                    "label" => "Ensure the Repositories Directory exists",
                    "path" => REPODIR
                ), ), ),

                array ( "Chmod" => array( "path" => array(
                    "label" => "Ensure the Repositories Directory is writable",
                    "path" => REPODIR,
                    "recursive" => true,
                    "mode" => '0755',
                ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuration Management for Pharaoh Source Complete"),),),

            );

    }

}
