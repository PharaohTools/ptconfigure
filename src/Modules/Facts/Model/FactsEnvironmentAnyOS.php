<?php

Namespace Model;

class FactsEnvironmentAnyOS extends FactsAnyOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Environment") ;

    public function getAllAvailableFactNamesAndMethods() {
        $all_fact_names = array(
            "target" => "factGetCwd",
            "getcwd" => "factGetCwd",
            "constant" => "factGetConstant"
        );
        return $all_fact_names ;
    }

    public function findTargetFrom($env_name, $target_scope = null) {
        $loggingFactory = new \Model\Logging() ;
        $logging = $loggingFactory->getModel($this->params) ;
        $logging->log("Trying to find target from {$env_name} ", $this->getModuleName()) ;
//        $env_level = $this->findCompleteSlug()."-{$target_type}" ;
        $conf = \Model\AppConfig::getProjectVariable("environments") ;
        if ($target_scope == "public") { $target_scope_string = "target_public" ; }
        else if ($target_scope == "private") { $target_scope_string = "target_private" ; }
        else { $target_scope_string = "target" ; }
        $target = null ;
        foreach ($conf as $one_env) {
            if ($one_env["any-app"]["gen_env_name"] == $env_name) {
                $cou = count($one_env["servers"]) - 1 ;
                $target = $one_env["servers"][$cou][$target_scope_string]; } }
        return $target ;
    }
}

