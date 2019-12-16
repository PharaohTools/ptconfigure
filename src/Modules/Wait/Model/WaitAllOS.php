<?php

Namespace Model;

class WaitAllOS extends BaseLinuxApp {

    // Compatibility
    public $os = array('any') ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function performWait() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $wait_amout = $this->getWaitAmount() ;
        $logging->log("Performing Wait of {$wait_amout} seconds...", $this->getModuleName());
        sleep($wait_amout) ;
        $logging->log("Wait Completed Successfully...", $this->getModuleName());
        return true;
    }

    protected function getWaitAmount() {
        if (isset($this->params["seconds"])) { return $this->params["seconds"] ; }
        if (isset($this->params["sec"])) { return $this->params["sec"] ; }
        return 10 ;
    }

}