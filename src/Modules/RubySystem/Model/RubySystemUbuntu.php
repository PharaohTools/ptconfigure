<?php

Namespace Model;

class RubySystemUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "RubySystem";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array( "Apt", array("ruby") ) ) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array( "Apt", array("ruby") ) ) ),
        );
        $this->programDataFolder = "/opt/rubysystem";
        $this->programNameMachine = "ruby"; // command and app dir name
        $this->programNameFriendly = "Ruby System!"; // 12 chars
        $this->programNameInstaller = "Ruby, System Wide";
        $this->initialize();
    }

}