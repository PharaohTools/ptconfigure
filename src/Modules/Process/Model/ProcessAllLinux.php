<?php

Namespace Model;

class ProcessAllLinux extends Base {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
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
        if ($nameOrId == "name") { $res = $this->doProcessKillByName() ; }
        else { $res = $this->doProcessKillByIds() ; }
        return $res;
    }

    private function doProcessKillByName() {
        if (isset($this->params["use-pkill"])) { $res = $this->doProcessKillByPkill() ; }
        else { $res = $this->doProcessKillByPsax() ; }
        return $res ;
    }

    private function doProcessKillByIds() {
        $ids = $this->getIds() ;
        $res = array() ;
        foreach ($ids as $id) {
            $res[] = $this->doSingleProcessKillById($id) ; }
        if (count($ids)==0) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("No process found by pid, no pid found", $this->getModuleName());
            return false; }
//        var_dump("res", $res, $ids ) ;
        if (in_array(false, $res)) { return false ; }
        return true ;
    }

    private function doProcessKillByPsax() {
        $names = $this->getNames() ;
        $rcs = array() ;
        foreach ($names as $name) {
            $comm = "ps ax | grep {$name}" ;
            $psaxout = self::executeAndLoad($comm) ;
            $lines = explode("\n", $psaxout) ;
            foreach ($lines as $line) {
                if (strpos($line, $name) !== false
                    && strpos($line, " grep ") == false
                    && strpos($line, " process kill ") == false ) {
                    $id = $this->getIdFromPsaxLine($line) ;
                    $rcs[] = $this->doSingleProcessKillById($id) ; } }
            if (count($rcs)==0) {
                $loggingFactory = new \Model\Logging();
                $logging = $loggingFactory->getModel($this->params);
                $logging->log("No process found by ps ax matching {$name}", $this->getModuleName()); } }


        // var_dump("rcs1", $rcs) ;
        if ($this->inArrayAll(true, $rcs)) { return true ; }
        return false ;
    }

    private function doProcessKillByPkill() {
        $names = $this->getNames() ;
        $rcs = array() ;
        foreach ($names as $name) {
            $comm = SUDOPREFIX."pkill $name" ;
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Executing $comm", $this->getModuleName());
            $one_rc = self::executeAndGetReturnCode($comm, true, false) ;
            $rcs[] = $one_rc["rc"] ; }
        // var_dump("rcs", $rcs) ;
        if ($this->inArrayAll("0", $rcs)) { return true ; }
        return false ;
    }

    private function doSingleProcessKillById($id) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Killing process id $id", $this->getModuleName());
        $level = $this->getKillLevel();
        $comm = SUDOPREFIX."kill -$level $id" ;
        $rc = self::executeAndGetReturnCode($comm, true, false) ;
        if ($rc["rc"] == "0") { return true ; }
        return false ;
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
        $names = $this->getNames() ;
        if (count($names)>0) { return "name" ; }
        else { return "id"; }
    }

    private function getNames(){
        $names = array() ;
        for ($i = 1; $i<100; $i++) {
            if (isset($this->params["name$i"])) { $names[] = $this->params["name$i"] ; }
            else { break ; } }
        if (isset($this->params["name"])) {
            $names = array_merge($names, explode(',', $this->params["name"])) ; }
        if (isset($this->params["names"])) {
            $names = array_merge($names, explode(',', $this->params["names"])) ; }

        // var_dump("names", $names) ;

        return $names;
    }

    private function getIds(){
        $ids = array() ;
        for ($i = 1; $i<100; $i++) {
            if (isset($this->params["id$i"])) { $names[] = $this->params["id$i"] ; }
            else { break ; } }
        return $ids;
    }

    private function getIdFromPsaxLine($line){
        $pid = substr($line, 0, strpos($line, " ")) ;
        return $pid;
    }

    private function inArrayAll($value, $array) {
        return (reset($array) == $value && count(array_unique($array)) == 1);
    }


}