<?php

Namespace Model;

class CopyAllLinux extends Base {

    // Compatibility
    public $os = array("Linux") ;
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
        $this->doCopyPut($sourcePath, $targetPath) ;
        return true;
    }

    private function doCopyPut($source, $target) {
        $comm = "cp -r $source $target" ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Executing $comm", $this->getModuleName());
        self::executeAndOutput($comm) ;
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