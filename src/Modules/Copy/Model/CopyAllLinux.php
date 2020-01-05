<?php

Namespace Model;

class CopyAllLinux extends Base {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function askWhetherToCopyPut() {
        return $this->performCopyPut();
    }

    public function performCopyPut() {
        if ($this->askForCopyExecute() != true) { return false; }
        $sourcePath = $this->getSourceFilePath() ;
        $targetPath = $this->getTargetFilePath() ;
        return $this->doCopyPut($sourcePath, $targetPath) ;
    }

    public function doCopyPut($source, $target) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (strrpos($source, '/') !== (strlen($source)-1) &&
            strrpos($source, '*') !== (strlen($source)-1) ) {
            $logging->log("Copying file from $source to $target", $this->getModuleName());
            $res = copy($source, $target) ;
            if ($res !== true) {
                $logging->log("Copying file to $target failed", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
            }
            return $res ;
        } else {
            $comm = "cp -r $source $target" ;
            $logging->log("Executing $comm", $this->getModuleName());
            $rc = self::executeAndGetReturnCode($comm, true, false) ;
            return ($rc["rc"]==0) ? true : false ;
        }
    }

    private function askForCopyExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Copy files?';
        return self::askYesOrNo($question);
    }

    private function getSourceFilePath(){
        if (isset($this->params["source"])) { return $this->params["source"] ; }
        else { $question = "Enter source file path"; }
        $input = self::askForInput($question) ;
        return ($input=="") ? false : $input ;
    }

    private function getTargetFilePath(){
        if (isset($this->params["target"])) { return $this->params["target"] ; }
        else { $question = "Enter target file path"; }
        $input = self::askForInput($question) ;
        return ($input=="") ? false : $input ;
    }
}