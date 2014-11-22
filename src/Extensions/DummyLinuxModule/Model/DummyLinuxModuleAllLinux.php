<?php

Namespace Model;

class DummyLinuxModuleAllLinux extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "DummyLinuxModule";
        $this->installCommands = array( "ls" );
        $this->uninstallCommands = array( "ls" );
        $this->programDataFolder = "/opt/DummyLinuxModule"; // command and app dir name
        $this->programNameMachine = "DummyLinuxModule"; // command and app dir name
        $this->programNameFriendly = "Dummy Module"; // 12 chars
        $this->programNameInstaller = "Dummy Module";
        $this->initialize();
    }

}
