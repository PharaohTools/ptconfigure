<?php

Namespace Model;

class PortCentOS extends PortAllDebianMac {

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
        if ($yum->isInstalled("lsof")) {
            $logging->log("Package lsof is already installed", $this->getModuleName()) ;
            return true; }
        $logging->log("Package lsof not installed, installing...", $this->getModuleName()) ;
        return $yum->installPackage("lsof") ;
    }

}