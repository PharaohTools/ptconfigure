<?php

Namespace Model;

class FactsRuntimeAnyOS extends FactsAnyOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Runtime") ;

    public function getAllAvailableFactNamesAndMethods() {
        $all_fact_names = array(
            "cwd" => "factGetCwd",
            "getcwd" => "factGetCwd",
            "constant" => "factGetConstant",
            "envvar" => "envVar",
            "env_var" => "envVar"
        );
        return $all_fact_names ;
    }

    public function factGetCwd() {
        $cwd = getcwd() ;
        return $cwd ;
    }

    public function factGetConstant($const) {
        return constant($const) ;
    }

    public function envVar($env_var) {
        $res = getenv($env_var) ;
        if ($res !== false) {
            return $res ;
        } else {
            return false ;
        }
    }

}

