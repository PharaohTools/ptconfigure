<?php

Namespace Model;

class ServiceCentos extends ServiceDebian {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Redhat") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    public function runAtReboots() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Adding {$this->serviceName} service startup links") ;
        $rc = $this->executeAndGetReturnCode(SUDOPREFIX."chkconfig {$this->serviceName} on", true);
        return ($rc == 0) ? true : false;
    }

}