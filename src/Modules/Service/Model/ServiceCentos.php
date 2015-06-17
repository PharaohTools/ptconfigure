<?php

Namespace Model;

class ServiceCentos extends BaseLinuxApp {

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
        $this->executeAndOutput(SUDOPREFIX."chkconfig {$this->serviceName} on");
        return true ;
    }

}