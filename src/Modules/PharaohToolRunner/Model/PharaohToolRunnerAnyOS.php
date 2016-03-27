<?php

Namespace Model;

class PharaohToolRunnerAnyOS extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function askWhetherToPharaohToolRunnerPut() {
        return $this->performPharaohToolRunnerPut();
    }

    public function performPharaohToolRunnerPut() {
        if ($this->askForPharaohToolRunnerExecute() != true) { return false; }
        $tool = $this->getNameOfToolToRun() ;
        $tool = $this->parseAvailableTools($tool);
        $module = $this->getNameOfModuleToRun() ;
        $action = $this->getNameOfActionToRun() ;
        return $this->doPharaohToolRun($tool, $module, $action) ;
    }

    protected function doPharaohToolRun($tool, $module, $action) {
        $comm = "$tool $module $action" ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Executing $comm", $this->getModuleName());
        $rc = self::executeAndGetReturnCode($comm, true, false) ;
        return ($rc["rc"]==0) ? true : false ;
    }

    protected function askForPharaohToolRunnerExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Run command in a Pharaoh Tool?';
        return self::askYesOrNo($question);
    }

    protected function getNameOfToolToRun(){
        if (isset($this->params["tool"])) { return $this->params["tool"] ; }
        else { $question = "Enter tool name"; }
        $input = self::askForInput($question) ;
        return ($input=="") ? false : $input ;
    }

    protected function parseAvailableTools($tool){
        $all_tool_synonyms = array();
        $all_tool_synonyms[PTCCOMM] = array("ptconfigure", "configure", "config", "ptc", "ptconfigure.cmd") ;
        $all_tool_synonyms[PTBCOMM] = array("ptbuild", "build", "ptb", "ptbuild.cmd") ;
        $all_tool_synonyms[PTDCOMM] = array("ptdeploy", "deploy", "ptd", "ptdeploy.cmd") ;
        $all_tool_synonyms[PTVCOMM] = array("ptvirtualize", "virtualize", "ptv", "develop", "virtualize.cmd") ;
        $all_tool_synonyms[PTTRCOMM] = array("pttrack", "track", "pttr", "pttrack.cmd") ;
        $all_tool_synonyms[PTTECOMM] = array("pttest", "test", "ptte", "pttest.cmd") ;
        foreach ($all_tool_synonyms as $final_tool => $cur_synonyms) {
            if (in_array($tool, $cur_synonyms)) {
                $tool = $final_tool ; } }
        return $tool ;
    }

    protected function getNameOfModuleToRun(){
        if (isset($this->params["module"])) { return $this->params["module"] ; }
        else { $question = "Enter module name"; }
        $input = self::askForInput($question) ;
        return ($input=="") ? false : $input ;
    }

    protected function getNameOfActionToRun(){
        if (isset($this->params["action"])) { return $this->params["action"] ; }
        else { $question = "Enter action name"; }
        $input = self::askForInput($question) ;
        return ($input=="") ? false : $input ;
    }

    protected function getParametersToForward(){
        if (isset($this->params["params"])) {
            $res = $this->transformOurParams($this->params["params"]) ;
            return $res ; }
        else { $question = "Enter parameter string"; }
        $input = self::askForInput($question) ;
        $res = $this->transformOurParams($input) ;
        return $res ;
    }

    protected function transformOurParams($pstr) {
        $pairs = explode(",", $pstr) ;
        $parameter_string = "" ;
        foreach ($pairs as $pair) {
            if (strpos($pair, ":") !== false) {
                $key = substr($pair, 0, strpos($pair, ":") ) ;
                $val = substr($pair, strpos($pair, ":") + 1 ) ;
                $parameter_string .= " --{$key}={$val}" ; }
            else {
                $parameter_string .= " --{$pair}" ; } }
        return $parameter_string ;
    }

}