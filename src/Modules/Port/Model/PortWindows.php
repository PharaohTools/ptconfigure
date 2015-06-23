<?php

Namespace Model;

class PortWindows extends PortAllDebianMac {

    // Compatibility
    public $os = array("Windows", "WINNT") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    protected function getPortService() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ($this->installDependencies() == false) { return false ;}
        $comm = 'netstat -anb  | find "'.$this->portNumber.'"';
        $out = self::executeAndGetReturnCode($comm, false, true) ;

        $process = $this->findProcess($out);

        if ($out["rc"] != "0") {
            \Core\BootStrap::setExitCode(1);
            $logging->log("Port process command execution failed.", $this->getModuleName()) ;
            return true; }
        if (isset($process)) {
            $logging->log("Port {$this->portNumber} is being used by the process {$process}", $this->getModuleName()) ;
            if (isset($this->params["expect"])) {
                $logging->log("Expecting process to be {$this->params["expect"]}", $this->getModuleName()) ;
                if ($this->params["expect"] != $process ) {
                    $logging->log("Wrong process on Port {$this->portNumber}", $this->getModuleName()) ;
                    \Core\BootStrap::setExitCode(1);
                    return false ; } }
            $logging->log("Expected process found", $this->getModuleName()) ;
            return true; }
        else {
            $logging->log("Port {$this->portNumber} is not being used by a process", $this->getModuleName()) ;
            return false; }
    }

    protected function findProcess($out) {
        $startChar=0;
        $sys = new \Model\SystemDetectionAllOS() ;
        for ($i=0; $i<count($out["output"]); $i++) {
            $hasChar = strpos($out["output"][$i], "Local Address") ;
            if ($hasChar !==false) {
                $startChar = $hasChar ; }
            else {
                $endStr = substr($out["output"][$i], $startChar) ;
                $possible = substr($out["output"][$i], $startChar, strpos($endStr, " ")) ;
                foreach ($sys->ipAddresses as $ip) {
                    if ($possible == "{$ip}:{$this->portNumber}") {
                        return $this->getServiceNameFromOutput($out["output"][$i+1]); } } } }
        return null ;
    }

    protected function getServiceNameFromOutput($out) {
        $start = strpos($out, "[") ;
        $end = strpos($out, "]") ;
        $str = substr($out, $start, $end) ;
        return $str ;
    }

}