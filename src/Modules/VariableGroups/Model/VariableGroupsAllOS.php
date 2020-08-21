<?php

Namespace Model;

class VariableGroupsAllOS extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("any") ;

    public function __construct($params) {
        parent::__construct($params);
    }

    public function getVariables() {
        $vg = $this->getVariableGroups() ;
        $variables = array() ;
        if (is_array($vg) && count($vg)>0) {
            $variables = array() ;
            foreach ($vg as $group) {
                $set = $this->loadVariableSet($group, $variables) ;
                $variables = array_merge($variables, $set) ; } }
        return $variables ;
    }

    public function dump() {
        $vars = $this->getVariables() ;
        asort($vars, SORT_ASC) ;
        echo "\n" ;
        echo "Dumping all loaded Variables: \n" ;
        echo "----------------------------- \n" ;
        foreach ($vars as $key => $value) {
            if (is_string($value) ) {
                echo "  $key => $value \n" ;
            } else {
                echo "  $key => ".var_export($value, true)." \n" ;
            }
        }

        return true ;
    }

    protected function getVariableGroups() {
        $vg = null ;
        if (isset($this->params["vargroups"])) { $vg = $this->params["vargroups"] ; }
        if (isset($this->params["vars"])) { $vg = $this->params["vars"] ; }
        if (isset($this->params["variables"])) { $vg = $this->params["variables"] ; }
        if (is_array($vg)) { return $vg ; }
        if (!is_null($vg)) { return explode(",", $vg) ; }
        return $vg ;
    }

    protected function loadVariableSet($set, $variables) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel(array());
        $set = $this->findVariableFilePath($set) ;
        if (file_exists($set)) {
            $ext = pathinfo($set, PATHINFO_EXTENSION);
            switch ($ext) {
                case "php" :
                    $full_set = realpath($set); ;
                    $params = (isset($this->params["raw"])) ? $this->params["raw"] : array() ;
                    $logging->log("Loading Variable Set from {$full_set}", $this->getModuleName());
                    if (file_exists($full_set)) {
                        require $full_set ;
                    } else {
                        $logging->log("Unable to load Variable Set from {$full_set}", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
                    }
                    break;
                default:
                    break; } }
        else {
            $logging->log("Unable to load Variable Set from {$set}", $this->getModuleName());
        }

        if (isset($variables) && (is_array($variables))) { return $variables ; }
        return array();
    }

    protected function findVariableFilePath($set) {
        if (file_exists($set)) {
            return $set ; }
        else if (file_exists(getcwd().DS.$set)) { return getcwd().DS.$set ; }
        return null;
    }

}