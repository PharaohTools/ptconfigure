<?php

Namespace Model;

class UbuntuCompilerUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "UbuntuCompiler";
        $this->installCommands = array( "apt-get install -y c++ build-essential make" );
        $this->uninstallCommands = array( "apt-get remove -y c++ build-essential make" );
        $this->programDataFolder = "/opt/UbuntuCompiler"; // command and app dir name
        $this->programNameMachine = "ubuntucompiler"; // command and app dir name
        $this->programNameFriendly = "Ubuntu Comp!"; // 12 chars
        $this->programNameInstaller = "Ubuntu Compiler";
        $this->initialize();
    }

    public function askStatus() {
        $stat1 = $this->askStatusByArray( array("make") ) ;
        $aptFactory = new Apt();
        $aptModel = $aptFactory->getModel($this->params) ;
        $stat2 = $aptModel->isInstalled("build-essential") ;
        return ($stat1==true && $stat2==true) ? true : false ;
    }
}