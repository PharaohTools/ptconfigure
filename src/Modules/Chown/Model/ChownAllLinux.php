<?php

Namespace Model;

class ChownAllLinux extends Base {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function askWhetherToChown() {
        return $this->performChown();
    }

    public function performChown() {
        if ($this->askForChownExecute() != true) { return false; }
        $dirPath = $this->getDirectoryPath() ;
        $this->doChown($dirPath) ;
        return true;
    }

    private function doChown($dirPath) {
        $recursive = (isset($this->params["recursive"])) ? true : false ;
        $mode = $this->getMode() ;
        $result = mkdir($dirPath, $mode, $recursive);
        return $result ;
    }

    private function askForChownExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Chown files?';
        return self::askYesOrNo($question);
    }

    private function getDirectoryPath(){
        if (isset($this->params["dir"])) { return $this->params["dir"] ; }
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