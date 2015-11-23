<?php

Namespace Model;

class ApacheDefaultsCentos extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Redhat") ;
    public $distros = array("any") ;
    public $versions = array( array("5.9", "+")) ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->installCommands = array(
//            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Yum", $this->packages ) ) ),
        );
        $this->uninstallCommands = array();
        $this->programDataFolder = "/opt/ApacheDefaults"; // command and app dir name
        $this->programNameMachine = "ApacheDefaults"; // command and app dir name
        $this->programNameFriendly = "Apache Defaults!"; // 12 chars
        $this->programNameInstaller = "Apache Default Settings";
        $this->statusCommand = "exit 1" ;
        $this->initialize();
    }

}
