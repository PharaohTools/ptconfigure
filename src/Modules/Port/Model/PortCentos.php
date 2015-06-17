<?php

Namespace Model;

class PortCentOS extends PortAllOS {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Redhat") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    protected function installDependencies() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Ensuring Redhat based dependency for Port Process check, package lsof", $this->getModuleName()) ;
        $yumFactory = new \Model\Yum();
        $yum = $yumFactory->getModel($this->params);
        return $yum->installPackage("lsof") ;
    }

}