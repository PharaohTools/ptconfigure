<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;

    public function __construct() {
        $this->setSteps();
    }

    /* Steps */
    private function setSteps() {

        include ("settings.php") ;

        $this->steps =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Manage Configuration on the Git Environment" ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Prep Ubuntu on the Git Environment" ),),),
                array ( "Autopilot" => array("execute" => array(
                    "autopilot-file" => "{$parent}build/config/cleopatra/autopilots/medium-git-prep-ubuntu.php",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets Invoke Cleo and Dapper on the Git Environment" ),),),
                array ( "Autopilot" => array("execute" => array(
                    "autopilot-file" => "{$parent}build/config/cleopatra/autopilots/medium-git-invoke-cleo-dapper-new.php",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets setup Git Box on the Git Environment" ),),),
                array ( "Autopilot" => array("execute" => array(
                    "autopilot-file" => "{$parent}build/config/cleopatra/autopilots/medium-git-invoke-git.php",
                ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Managing Configuration on Git environment complete"),),),
            );

    }

}
