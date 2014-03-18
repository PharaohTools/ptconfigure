<?php

Namespace Model;

class StateDetectionUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;


    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "StateDetection";
        $this->installCommands = array("apt-get install -y apache2");
        $this->uninstallCommands = array("apt-get remove -y apache2");
        $this->programDataFolder = "/opt/StateDetection"; // command and app dir name
        $this->programNameMachine = "apacheinstall"; // command and app dir name
        $this->programNameFriendly = "Apache Install!"; // 12 chars
        $this->programNameInstaller = "Apache Install";
        $this->initialize();
      }

}