<?php

Namespace Model;

class CopyWindows extends CopyAllLinux {

    // Compatibility
    public $os = array("Windows", 'WINNT') ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function doCopyPut($source, $target) {
        $comm = "copy $source $target" ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Executing $comm", $this->getModuleName());
        $rc = self::executeAndGetReturnCode($comm, true, false) ;
        return ($rc["rc"]==0) ? true : false ;
    }

}