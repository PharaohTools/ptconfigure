<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;

    public function __construct() {
        $this->setSteps();
    }

    /* Steps */
    private function setSteps() {
        include(dirname(__DIR__)).DS."settings.php" ;
        $this->steps =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Creating a tiny set of environments"),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add all Boxes and Environments in Parallel" ),),),
                array ( "Parallax" => array("cli" => $this->getParallelCommands()),),
                array ( "Logging" => array( "log" => array( "log-message" => "Creating a medium, web only set of environments complete"),),),
            );
    }

    private function getParallelCommands() {
        include(dirname(__DIR__)).DS."settings.php" ;
        $types = array ($bastion_env, $build_env, $staging_env, $production_env, $git_env) ;
        $comray = array();
        $parent = __DIR__.DS ;
        for ($i=1;$i<=count($types);$i++) {
            $comray["command-{$i}"] = PTCCOMM." auto x --af=\"{$parent}boxify-add-tiny-{$types[$i-1]}.php\"" ; }
        return $comray ;
    }

}
