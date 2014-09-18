<?php

Namespace Model;

class NetworkToolsUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "NetworkTools";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "traceroute")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "netstat")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "lsof")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "telnet")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "ps")) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "traceroute")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "netstat")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "lsof")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "telnet")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "ps")) ),
        );
        $this->programDataFolder = "";
        $this->programNameMachine = "networktools"; // command and app dir name
        $this->programNameFriendly = "!Network Tools!!"; // 12 chars
        $this->programNameInstaller = "Network Tools";
        $this->initialize();
    }

    public function askStatus() {
        return $this->askStatusByArray(array("traceroute", "netstat", "lsof",  "telnet", "ps")) ;
    }

}
