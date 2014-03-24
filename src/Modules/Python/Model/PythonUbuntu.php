<?php

Namespace Model;

class PythonUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "Python";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "python", "python-docutils")) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "python", "python-docutils")) ),
        );
        $this->programDataFolder = "";
        $this->programNameMachine = "python"; // command and app dir name
        $this->programNameFriendly = "!Python!!"; // 12 chars
        $this->programNameInstaller = "Python";
        $this->initialize();
    }

}