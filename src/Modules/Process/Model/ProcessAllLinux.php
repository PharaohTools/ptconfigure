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

    public function askWhetherToProcessKill() {
        return $this->performProcessKill();
    }

    public function performProcessKill() {
        if ($this->askForProcessExecute() != true) { return false; }
        $nameOrId = $this->getNameOrId() ;
        if ($nameOrId == "name") { $this->doProcessKillByName() ; }
        else {$this->doProcessKillByIds() ; }
        return true;
    }

    private function doProcessKillByName() {
        if (isset($this->params["use-pkill"])) { $this->doProcessKillByPkill() ; }
        else { $this->doProcessKillByPsax() ; }
    }

    private function doProcessKillByIds() {
        $ids = $this->getIds() ;
        foreach ($ids as $id) {
            $this->doSingleProcessKillById($id) ;}
    }

    private function doProcessKillByPsax() {
        $names = $this->getNames() ;
        $comm = "ps ax | grep \"{$this->params["name1"]}\"" ;
        $psaxout = self::executeAndLoad($comm) ;
        $lines = explode("\n", $psaxout) ;
        foreach ($lines as $line) {
            foreach ($names as $name) {
                if (strpos($line, $name) !== false && strpos($line, " grep ") == false ) {
                    $id = $this->getIdFromPsaxLine($line) ;
                    $this->doSingleProcessKillById($id) ; } } }
    }

    private function doProcessKillByPkill() {
        $names = $this->getNames() ;
        foreach ($names as $name) {
            $comm = "sudo pkill $name" ;
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Executing $comm", $this->getModuleName());
            self::executeAndOutput($comm) ; }
    }

    private function doSingleProcessKillById($id) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Killing process id $id", $this->getModuleName());
        $level = $this->getKillLevel();
        $comm = "sudo kill -$level $id" ;
        self::executeAndOutput($comm) ;
    }

    private function askForProcessExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Perform Process Function?';
        return self::askYesOrNo($question);
    }

    private function getKillLevel(){
        if (isset($this->params["level"])) {
            return $this->params["level"] ; }
        else if (isset($this->params["guess"])) {
            return 9 ; }
        else {
            $question = 'Enter Kill Level';
            return self::askForInput($question); }
    }

    private function getNameOrId(){
        if (isset($this->params["name1"])) { return "name" ; }
        else { return "id"; }
    }

    private function getNames(){
        $names = array() ;
        for ($i = 0; $i<100; $i++) {
            if (isset($this->params["name$i"])) { $names[] = $this->params["name$i"] ; }
            else { break ; } }
        return $names;
    }

    private function getIds(){
        $ids = array() ;
        for ($i = 0; $i<100; $i++) {
            if (isset($this->params["id$i"])) { $names[] = $this->params["id$i"] ; }
            else { break ; } }
        return $ids;
    }

    private function getIdFromPsaxLine($line){
        $pid = substr($line, 0, strpos($line, " ")) ;
        return $pid;
    }
}