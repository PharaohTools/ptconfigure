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
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a Tiny set of environments"),),),

                // Bastion
                array ( "Logging" => array( "log" => array( "log-message" => "Lets add all Boxes and Environments in Parallel" ),),),
                array ( "Parallax" => array("cli" => $this->getParallelCommands() )),
                array ( "Logging" => array( "log" => array( "log-message" => "Configuring a Tiny set of environments complete"),),),

            );

    }



    private function getParallelCommands() {
        include(dirname(__DIR__)).DS."settings.php" ;
        $envs = array ("bastion"=>$bastion_env, "build"=>$build_env, "staging"=>$staging_env, "production"=>$production_env, "git"=>$git_env) ;
        $types = array_keys($envs);
        $comray = array();
        $parent = __DIR__.DS ;
        for ($i=1;$i<=count($types);$i++) {
            $comray["command-{$i}"] = PTCCOMM." auto x --af=\"{$parent}cleofy-cm-{$types[$i-1]}.php\"" ; }
        return $comray ;
    }

}

/*
 *                 array(
                    "command-1"  => "ptconfigure autopilot execute --autopilot-file=\"{$parent}cleofy-cm-bastion.php\"",
                    "command-2"  => "ptconfigure autopilot execute --autopilot-file=\"{$parent}cleofy-cm-git.php\"",
                    "command-3"  => "ptconfigure autopilot execute --autopilot-file=\"{$parent}cleofy-cm-build.php\"",
                    "command-4"  => "ptconfigure autopilot execute --autopilot-file=\"{$parent}cleofy-cm-staging.php\"",
                    "command-5"  => "ptconfigure autopilot execute --autopilot-file=\"{$parent}cleofy-cm-production.php\"",
                ),),),
 */