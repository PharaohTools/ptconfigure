<?php

Namespace Model;

class FactsAnyOS extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function find($name = null, $var_string = null) {
        $availableFacts = $this->getAvailableFactNames() ;
        $availableFactMethods = $this->getAvailableFactNamesAndMethods() ;
        $factToFind = $this->getFactNameToFind($name) ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        if ($factToFind == false) {
            $logging->log("Unable to find requested fact: {$factToFind}", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ; }

        if (in_array($factToFind, $availableFacts)) {
            $logging->log("Fact available {$factToFind}", $this->getModuleName()) ;
            if (method_exists($this, $availableFactMethods[$factToFind])) {
                $logging->log("Found fact method", $this->getModuleName()) ;
                $meth = $availableFactMethods[$factToFind] ;
                $var_string_param = explode(",", $var_string) ;
                return $this->$meth(extract($var_string_param)) ; }
            else {
                $logging->log("Method {$availableFactMethods[$factToFind]} does not exist when reporting it does ", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                return false ;  } }
        else {
            $logging->log("Requested fact {$factToFind} not available", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ; }

    }

    public function getAvailableFactNamesAndMethods() {
        if (method_exists($this, 'getAllAvailableFactNamesAndMethods')) {
            $fnm = $this->getAllAvailableFactNamesAndMethods(); }
        else {
            $fnm = array() ; }
        return $fnm ;
    }

    public function getAvailableFactNames() {
        if (method_exists($this, 'getAllAvailableFactNamesAndMethods')) {
            $fnm = $this->getAllAvailableFactNamesAndMethods(); }
        else {
            $fnm = $this->getAvailableFactNamesAndMethods() ; }
        $fn = array_keys($fnm) ;
        return $fn ;
    }

    public function getFactNameToFind($name = null) {
        if ($name !== null) { return $name ;}
        if (isset($this->params['fact-name'])) { return $this->params['fact-name'] ; }
        if (isset($this->params['name'])) { return $this->params['name'] ; }
        return false ;
    }

}

