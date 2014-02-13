<?php

Namespace Model;

class NodeJSUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "NodeJS";
        $this->installCommands = array( "apt-get install -y node nodejs" );
        $this->uninstallCommands = array( "apt-get remove -y node nodejs" );
        $this->programDataFolder = "/opt/NodeJS"; // command and app dir name
        $this->programNameMachine = "nodejs"; // command and app dir name
        $this->programNameFriendly = "Node JS!"; // 12 chars
        $this->programNameInstaller = "Node JS";
        $this->initialize();
      }

}