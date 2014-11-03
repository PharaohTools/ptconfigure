<?php

Namespace Model;

class ChgrpAllLinux extends Base {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function askWhetherToChgrp() {
        return $this->performChgrp();
    }

    public function performChgrp() {
        if ($this->askForChgrpExecute() != true) { return false; }
        $dirPath = $this->getDirectoryPath() ;
        $this->doChgrp($dirPath) ;
        return true;
    }

    private function doChgrp($dirPath) {
        $recursive = (isset($this->params["recursive"])) ? "-R " : "" ;
        $group = $this->getGroup() ;
        $comm = "chgrp $recursive{$group} $dirPath" ;
        self::executeAndOutput($comm) ;
    }

    private function askForChgrpExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Chgrp files?';
        return self::askYesOrNo($question);
    }

    private function getDirectoryPath(){
        if (isset($this->params["dir"])) { return $this->params["dir"] ; }
        else { $question = "Enter directory path:"; }
        $input = self::askForInput($question) ;
        return ($input=="") ? false : $input ;
    }

    private function getGroup(){
        if (isset($this->params["group"])) { return $this->params["group"] ; }
        else { $question = "Enter ownership group:"; }
        $input = self::askForInput($question, true) ;
        return $input ;
    }
}