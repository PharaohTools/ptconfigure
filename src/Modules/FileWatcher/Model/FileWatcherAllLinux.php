<?php

Namespace Model;

class FileWatcherAllLinux extends Base {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function askWhetherToFileWatcher() {
        return $this->performFileWatcher();
    }

    public function performFileWatcher() {
        if ($this->askForFileWatcherExecute() != true) { return false; }
        return $this->doSingleFileWatch() ;
    }

    public function doSingleFileWatch() {
        $fileToWatch = $this->getFileToWatch() ;
        $versioner = $this->getComparisonVersioner() ;
        $value = $this->getValue() ;
        $scb = $this->getCallbackCommand("success") ;
        $fcb = $this->getCallbackCommand("failure") ;
        $escalate = $this->getEscalation() ;
        // get watch fle name
        // get comparison type
        // get value to compare against
        // get callbacks to register
        // get value to compare against
        $compareStatus = $this->doComparison($fileToWatch, $versioner, $value);
        $cbStatus = $this->runAllCallbacks($compareStatus, $escalate, $scb, $fcb) ;
        return  $cbStatus;
    }

    private function askForFileWatcherExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Watcher files for changes?';
        return self::askYesOrNo($question);
    }

    private function getCallbackCommand($sf){
        if (isset($this->params["{$sf}-callback"])) { return $this->params["{$sf}-callback"] ; }
        else { return false ; }
    }

    private function getEscalation(){
        if (isset($this->params["escalate"])) { return true ; }
        else { return false ; }
    }

    private function getFileToWatch(){
        if (isset($this->params["file"])) { return $this->params["file"] ; }
        else { $question = "Enter single file to watch:"; }
        $input = self::askForInput($question, true) ;
        return $input ;
    }

    private function getComparisonVersioner(){
        if (isset($this->params["versioner"])) { return $this->params["versioner"] ; }
        else { $question = "Enter Versioner:"; }
        $input = self::askForInput($question, true) ;
        return $input ;
    }

    private function getValue(){
        if (isset($this->params["value"])) { return $this->params["value"] ; }
        else { $question = "Enter value to compare to:"; }
        $input = self::askForInput($question, true) ;
        return $input ;
    }

    private function doComparison($fileToWatch, $versioner, $value){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ($versioner == "git") {
            $changedFilesCommand = "git diff --name-only $value HEAD" ;
            $cfout = self::executeAndLoad($changedFilesCommand) ;
            $changedFilesRay = explode("\n", $cfout);
            if (in_array($fileToWatch, $changedFilesRay)) {
                $logging->log("Changed files does include file we're watching") ;
                return true; }
            else {
                $logging->log("Changed files does not include file we're watching") ;
                return false; } }
        else {
            $logging->log("Versioner not recognised, only git is supported") ;
            return false ;}
    }

    private function runAllCallbacks($compareStatus, $escalate, $scb, $fcb){
        if ($compareStatus == true) { // if file changed
            if ($scb !== false) { // and there is a success callback
                $callbackOut = $this->doCallback($scb, "Success") ; // run callback
                if ($escalate == true) { return $callbackOut ; } // escalation specified, return callback status
                else { return true ;  } } // no escalation specified, return status
            else {
                return true ; } } // no callback specified, so return status
        else { // if file not changed
            if ($fcb !== false) { // and there is a failure callback
                $callbackOut = $this->doCallback($fcb, "Failure") ; // run callback
                if ($escalate == true) { return $callbackOut ; } // escalation specified, return callback status
                else { return true ;  } } // no escalation specified, return status
            else {
                return true ; } } // no callback specified, so return status
    }

    private function doCallback($comm, $type){
        return self::executeAndGetReturnCode($comm, "Running ".ucfirst($type)." Callback");
    }

}