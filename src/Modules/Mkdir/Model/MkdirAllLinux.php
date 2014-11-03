<?php

Namespace Model;

class MkdirAllLinux extends Base {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function askWhetherToMkdir() {
        return $this->performMkdir();
    }

    public function performMkdir() {
        if ($this->askForMkdirExecute() != true) { return false; }
        $dirPath = $this->getDirectoryPath() ;
        $this->doMkdir($dirPath) ;
        return true;
    }

    private function doMkdir($dirPath) {
        $recursive = (isset($this->params["recursive"])) ? true : false ;
        $mode = $this->getMode() ;
        $result = mkdir($dirPath, $mode, $recursive);
        return $result ;
    }

    private function askForMkdirExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Mkdir files?';
        return self::askYesOrNo($question);
    }

    private function getDirectoryPath(){
        if (isset($this->params["path"])) { return $this->params["path"] ; }
        else { $question = "Enter directory path:"; }
        $input = self::askForInput($question) ;
        return ($input=="") ? false : $input ;
    }

    private function getMode(){
        if (isset($this->params["guess"])) { return 0777 ; }
        else { $question = "Enter permissions mode:"; }
        $input = self::askForInput($question) ;
        return ($input=="") ? false : $input ;
    }
}