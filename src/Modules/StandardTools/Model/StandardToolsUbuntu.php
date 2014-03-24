<?php

Namespace Model;

class StandardToolsUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "StandardTools";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array( "Apt", array("curl", "vim", "drush", "zip") ) ) ),
        ) ;
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array( "Apt", array("curl", "vim", "drush", "zip") ) ) ),) ;
        $this->programDataFolder = "/opt/StandardTools" ; // command and app dir name
        $this->programNameMachine = "standardtools" ; // command and app dir name
        $this->programNameFriendly = "Std. Tools!!" ; // 12 chars
        $this->programNameInstaller = "Standard Tools" ;
        $this->initialize() ;
    }

    public function askStatus() {
        return $this->askStatusByArray( array("curl", "vim", "drush", "zip")) ;
    }

}
