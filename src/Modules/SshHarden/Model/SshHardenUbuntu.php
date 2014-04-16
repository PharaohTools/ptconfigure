<?php

Namespace Model;

class SshHardenUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $sshhardenName ;
    protected $actionsToMethods = array("securify" => "askSecurify") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "SshHarden";
        $this->programDataFolder = "";
        $this->programNameMachine = "sshharden"; // command and app dir name
        $this->programNameFriendly = "!Ssh Harden!"; // 12 chars
        $this->programNameInstaller = "Ssh Hardening";
        $this->initialize();
    }

    protected function askSecurify() {
        $doSecurify = (isset($this->params["yes"]) && $this->params["yes"]==true) ?
            true : $this->askWhetherToInstallLinuxAppToScreen();
        if ($doSecurify == true) { return $this->securify(); }
        return false ;
    }

    protected function performSshHardenSecurify() {
        return $this->securify();
    }

    public function securify() {
        $this->doNotAllowRoot() ;
        $this->doNotAllowPlainTextPasswords() ;
        $this->restartService() ;
        return true ;
    }

    private function doNotAllowRoot() {
        $fileFactory = new \Model\File();
        $file = $fileFactory->getModel($this->params);
        $file->setFile('/etc/ssh/sshd_config') ;
        $file->replaceIfPresent(new RegExp("/^#?PermitRootLogin yes/m"), 'PermitRootLogin no');
        $file->shouldHaveLine("PermitRootLogin no");
        $loggingFactory = new \Model\Console();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("/etc/ssh/sshd_config modified to disallow root ssh login") ;
    }

    private function doNotAllowPlainTextPasswords() {
        $fileFactory = new \Model\File();
        $file = $fileFactory->getModel($this->params);
        $file->setFile('/etc/ssh/sshd_config') ;
        $file->replaceIfPresent(new RegExp("/^#?PasswordAuthentication yes/m"), 'PasswordAuthentication no');
        $file->shouldHaveLine("PasswordAuthentication no");
        $loggingFactory = new \Model\Console();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("/etc/ssh/sshd_config modified to disallow password based ssh login") ;
    }

    // @todo will restarting ssh break it all?
    private function restartService() {
        $serviceFactory = new \Model\Service();
        $service = $serviceFactory->getModel($this->params);
        $service->setService("ssh") ;
        $service->restart() ;
    }

}