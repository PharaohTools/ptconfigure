<?php

Namespace Model;

class ProcessAllLinux extends Base {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function askWhetherToProcessPut() {
        return $this->performProcessPut();
    }

    public function performProcessPut() {
        if ($this->askForProcessExecute() != true) { return false; }
        $sourcePath = $this->getSourceFilePath() ;
        $targetPath = $this->getTargetFilePath() ;
        $this->doProcessPut($sourcePath, $targetPath) ;
        return true;
    }

    private function doProcessPut($source, $target) {
        $comm = "cp -r $source $target" ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Executing $comm", $this->getModuleName());
        self::executeAndOutput($comm) ;
    }

    private function askForProcessExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Process files?';
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