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

                array ( "Logging" => array( "log" => array( "log-message" => "Lets ensure the MacPorts Package Manager is installed for this OSX System"),),),

                array ( "MacPorts" => array( "ensure" => array( ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Ensuring MacPorts Complete"),),),

            );

    }
}
