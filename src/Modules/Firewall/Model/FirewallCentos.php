<?php

Namespace Model;

class FirewallCentos extends FirewallUbuntu {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Redhat") ;
    public $distros = array("CentOS") ;
    public $versions = array(array("6.99", "-")) ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Firewall";
        $this->actionsToMethods = $this->setActionsToMethods() ;
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Yum", "firewalld")) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", "firewalld"))),
        );
        $this->uninstallCommands = array(""); // @todo uninstall for fwall
        $this->programDataFolder = "";
        $this->programNameMachine = "firewall"; // command and app dir name
        $this->programNameFriendly = "! Firewall !"; // 12 chars
        $this->programNameInstaller = "Firewall";
        $this->statusCommand = "which firewalld" ;
        $this->initialize();
    }

}